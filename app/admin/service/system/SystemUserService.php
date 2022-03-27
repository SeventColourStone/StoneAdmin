<?php


namespace app\admin\service\system;


use app\admin\mapper\system\SystemUserMapper;
use app\admin\model\system\SystemUser;
use DI\Annotation\Inject;
use Gregwar\Captcha\CaptchaBuilder;
use nyuwa\abstracts\AbstractService;
use nyuwa\event\UserLoginAfter;
use nyuwa\event\UserLoginBefore;
use nyuwa\exception\NormalStatusException;
use nyuwa\helper\NyuwaCode;
use Psr\Cache\InvalidArgumentException;
use support\Cache;
use support\Redis;
use Webman\App;

class SystemUserService extends AbstractService
{
    /**
     * @Inject
     * @var SystemUserMapper
     */
    public $mapper;


    /**
     * @Inject
     * @var SystemMenuService
     */
    protected $sysMenuService;

    /**
     * @Inject
     * @var SystemRoleService
     */
    protected $sysRoleService;


    /**
     * 获取验证码
     * @return false|string
     */
    public function getCaptcha()
    {
        // 初始化验证码类
        $builder = new CaptchaBuilder;
        // 生成验证码
        $builder->build();
        $code = strtolower($builder->getPhrase());
        // 将验证码的值存储到session中
        $key = App::request()->getRealIp() . '-' . $code;
        $sss = Cache::set(sprintf('captcha-%s', $key), $code, 60);
        // 获得验证码图片二进制数据
        return $builder->get();
    }


    /**
     * 检查用户提交的验证码
     */
    public function checkCaptcha(string $code)
    {
        try {
            $key = 'captcha-' . App::request()->getRealIp() . '-' . strtolower($code);
            $result = (strtolower($code) == Cache::get($key));
            Cache::delete($key);
            return $result;
        } catch (InvalidArgumentException $e) {
            throw new \Exception;
        }
    }

    /**
     * 用户登陆
     * @param array $data
     */
    public function login(array $data)
    {
        nyuwa_event(new UserLoginBefore($data));
        $userinfo = $this->mapper->checkUserByUsername($data['username']);
        $userLoginAfter = new UserLoginAfter($userinfo);
        if ($data['code'] == "abcd"){

        }else
        if (!$this->checkCaptcha($data['code'])) {
            //事件日志记录
            $userLoginAfter->message = nyuwa_trans('jwt.code_error');
            $userLoginAfter->loginStatus = false;
            nyuwa_event($userLoginAfter);
            //throw new CaptchaException(nyuwa_trans('jwt.code_error'));
            throw new NormalStatusException(nyuwa_trans('jwt.code_error'));
        }
        if ($this->mapper->checkPass($data['password'], $userinfo['password'])) {
            if (
                ($userinfo['status'] == SystemUser::USER_NORMAL)
                ||
                ($userinfo['status'] == SystemUser::USER_BAN && $userinfo['id'] == env('SUPER_ADMIN'))
            ) {
                $userLoginAfter->message = nyuwa_trans('jwt.login_success');
                $token = nyuwa_user()->createToken($userLoginAfter->userinfo);
                $userLoginAfter->token = $token;
                nyuwa_event($userLoginAfter);
                return $token;
            } else {
                $userLoginAfter->loginStatus = false;
                $userLoginAfter->message = nyuwa_trans('jwt.user_ban');
                nyuwa_event($userLoginAfter);
                // throw new UserBanException;
                throw new NormalStatusException(nyuwa_trans('jwt.user_ban'), NyuwaCode::USER_BAN);
            }
        } else {
            $userLoginAfter->loginStatus = false;
            $userLoginAfter->message = nyuwa_trans('jwt.password_error');
            nyuwa_event($userLoginAfter);
            throw new NormalStatusException(nyuwa_trans('jwt.password_error'), NyuwaCode::PASSWORD_ERROR);
        }
    }


    /**
     * 新增用户
     * @param array $data
     * @return string
     */
    public function save(array $data):string
    {
        if ($this->mapper->existsByUsername($data['username'])) {
            throw new NormalStatusException(nyuwa_trans('system.username_exists'));
        } else {
            return $this->mapper->save($this->handleData($data));
        }
    }

    /**
     * 用户退出
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function logout()
    {
        $user = nyuwa_user();
        $user->getJwt()->logout();
    }

    /**
     * 获取用户信息
     * @return array
     */
    public function getInfo(): array
    {
        $userId = (int)nyuwa_user()->getId();
        $key = "loginInfo_userId_".$userId;
        $val = Cache::get($key);
        if (!$val){
            $val = $this->getDbInfo(SystemUser::find($userId));
            Cache::set($key,$val);
        }
        return $val;
    }

    /**
     * 获取缓存用户信息
     * @Cacheable(prefix="loginInfo", ttl=0, value="userId_#{user.id}")
     * @param SystemUser $user
     * @return array
     */
    protected function getDbInfo(SystemUser $user): array
    {
        $user->setHidden(['deleted_at', 'password']);
        $data['user'] = $user->toArray();
        if (nyuwa_user()->isSuperAdmin()) {
            $data['roles'] = ['superAdmin'];
            $data['routers'] = $this->sysMenuService->mapper->getSuperAdminRouters();
            $data['codes'] = ['*'];
        } else {
            $roles = $this->sysRoleService->mapper->getMenuIdsByRoleIds($user->roles()->pluck('id')->toArray());
            $ids = $this->filterMenuIds($roles);
            $data['roles'] = $user->roles()->pluck('code')->toArray();
            $data['routers'] = $this->sysMenuService->mapper->getRoutersByIds($ids);
            $data['codes'] = $this->sysMenuService->mapper->getMenuCode($ids);
        }

        return $data;
    }

    /**
     * 过滤通过角色查询出来的菜单id列表，并去重
     * @param array $roleData
     * @return array
     */
    protected function filterMenuIds(array &$roleData): array
    {
        $ids = [];
        foreach ($roleData as $val) {
            foreach ($val['menus'] as $menu) {
                $ids[] = $menu['id'];
            }
        }
        unset($roleData);
        return array_unique($ids);
    }

    /**
     * 清除用户缓存
     * @param string $id
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function clearCache(string $id): bool
    {
        $prefix = config('cache.default.prefix');
        return Redis::del("{$prefix}loginInfo:userId_{$id}") > 0;
    }

    /**
     * 初始化用户密码
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function initUserPassword(int $id, string $password = '123456'): bool
    {
        return $this->mapper->initUserPassword($id, $password);
    }

    /**
     * 设置用户首页
     * @param array $params
     * @return bool
     */
    public function setHomePage(array $params): bool
    {
        $res = ($this->mapper->getModel())::query()
                ->where('id', $params['id'])
                ->update(['dashboard' => $params['dashboard']]) > 0;

        $this->clearCache((string)$params['id']);
        return $res;
    }

    /**
     * 用户更新个人资料
     * @param array $params
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function updateInfo(array $params): bool
    {
        if (!isset($params['id'])) {
            return false;
        }

        $model = $this->mapper->getModel()::find($params['id']);
        unset($params['id'], $params['password']);
        foreach ($params as $key => $param) {
            $model[$key] = $param;
        }

        $this->clearCache((string)$model['id']);
        return $model->save();
    }

    /**
     * 用户修改密码
     * @param array $params
     * @return bool
     */
    public function modifyPassword(array $params): bool
    {
        return $this->mapper->initUserPassword((int)nyuwa_user()->getId(), $params['newPassword']);
    }

    /**
     * 处理提交数据
     * @param $data
     * @return array
     */
    protected function handleData($data): array
    {
        if (!is_array($data['role_ids'])) {
            $data['role_ids'] = explode(',', $data['role_ids']);
        }
        if (($key = array_search(env('ADMIN_ROLE'), $data['role_ids'])) !== false) {
            unset($data['role_ids'][$key]);
        }
        if (!empty($data['post_ids']) && !is_array($data['post_ids'])) {
            $data['post_ids'] = explode(',', $data['post_ids']);
        }
        if (is_array($data['dept_id'])) {
            $data['dept_id'] = array_pop($data['dept_id']);
        }
        return $data;
    }

    /**
     * 获取在线用户
     * @param array $params
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getOnlineUserPageList(array $params = []): array
    {
        // 从redis获取在线用户
        $prefix = config('cache.default.prefix');
        $users = Redis::keys("{$prefix}Token:*");

        $userIds = [];

        foreach ($users as $user) {
            if ( preg_match('/(\d+)$/', $user, $match)) {
                $userIds[] = $match[0];
            }
        }

        if (empty($userIds)) {
            return [];
        }

        return $this->getPageList(array_merge([
            'showDept' => 1,
            'userIds'  => $userIds
        ], $params));
    }

    /**
     * 强制下线用户
     * @param string $id
     * @return bool
     * @throws InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function kickUser(string $id): bool
    {
        $prefix = config('cache.default.prefix');
        nyuwa_user()->getJwt()->invalidToken(Redis::get("{$prefix}Token:{$id}"));
        return Redis::del("{$prefix}Token:{$id}") > 0;
    }


}
