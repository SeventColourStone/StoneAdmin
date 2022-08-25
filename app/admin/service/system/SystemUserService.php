<?php

declare(strict_types=1);

namespace app\admin\service\system;


use app\admin\mapper\system\SystemUserMapper;
use app\admin\model\system\SystemUser;
use app\admin\service\setting\SettingConfigService;
use DI\Annotation\Inject;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use nyuwa\abstracts\AbstractService;
use nyuwa\event\UserLoginAfter;
use nyuwa\event\UserLoginBefore;
use nyuwa\exception\CaptchaException;
use nyuwa\exception\NormalStatusException;
use nyuwa\exception\NyuwaException;
use nyuwa\exception\UserBanException;
use nyuwa\helper\NyuwaCode;
use nyuwa\NyuwaEvent;
use nyuwa\vo\UserServiceVo;
use Psr\Cache\InvalidArgumentException;
use support\Cache;
use support\Log;
use support\Redis;
use Webman\App;
use Webman\Event\Event;

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
     * @Inject
     * @var SettingConfigService
     */
    protected $settingConfigService;

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
     * @throws \Exception
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
     * @param UserServiceVo $data
     */
    public function login(UserServiceVo $userServiceVo)
    {
        try {
            nyuwa_event(new UserLoginBefore(['username' => $userServiceVo->getUsername(), 'password' => $userServiceVo->getPassword()]));
            $userinfo = $this->mapper->checkUserByUsername($userServiceVo->getUsername())->toArray();
            $password = $userinfo['password'];
            unset($userinfo['password']);
            $userLoginAfter = new UserLoginAfter($userinfo);
            $webLoginVerify = $this->settingConfigService->getConfigByKey('web_login_verify');
            if (isset($webLoginVerify['value']) && $webLoginVerify['value'] === '1') {
                if ($userServiceVo->getVerifyCode() == "abcd"){
                    //临时放行

                }else
                if (! $this->checkCaptcha($userServiceVo->getVerifyCode())) {
                    $userLoginAfter->message = nyuwa_trans('jwt.code_error');
                    $userLoginAfter->loginStatus = false;
                    Event::emit(NyuwaEvent::USER_LOGIN_AFTER,$userLoginAfter);
                    throw new CaptchaException;
                }
            }
            if ($this->mapper->checkPass($userServiceVo->getPassword(), $password)) {
                if (
                    ($userinfo['status'] == SystemUser::USER_NORMAL)
                    ||
                    ($userinfo['status'] == SystemUser::USER_BAN && $userinfo['id'] == env('SUPER_ADMIN'))
                ) {
                    $userLoginAfter->message = nyuwa_trans('jwt.login_success');
                    $token = nyuwa_user()->createToken($userLoginAfter->userinfo);
                    $userLoginAfter->token = $token;
                    Event::emit(NyuwaEvent::USER_LOGIN_AFTER,$userLoginAfter);
                    return $token;
                } else {
                    $userLoginAfter->loginStatus = false;
                    $userLoginAfter->message = nyuwa_trans('jwt.user_ban');
                    Event::emit(NyuwaEvent::USER_LOGIN_AFTER,$userLoginAfter);
                    throw new UserBanException;
                }
            } else {
                $userLoginAfter->loginStatus = false;
                $userLoginAfter->message = nyuwa_trans('jwt.password_error');
                Event::emit(NyuwaEvent::USER_LOGIN_AFTER,$userLoginAfter);
                throw new NormalStatusException;
            }

        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new NormalStatusException(nyuwa_trans('jwt.username_error'), NyuwaCode::NO_DATA);
            }
            if ($e instanceof NormalStatusException) {
                throw new NormalStatusException(nyuwa_trans('jwt.password_error'), NyuwaCode::PASSWORD_ERROR);
            }
            if ($e instanceof UserBanException) {
                throw new NormalStatusException(nyuwa_trans('jwt.user_ban'), NyuwaCode::USER_BAN);
            }
            if ($e instanceof CaptchaException) {
                throw new NormalStatusException(nyuwa_trans('jwt.code_error'));
            }
            throw new NormalStatusException(nyuwa_trans('jwt.unknown_error'));
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

    public function update(string $id, array $data): bool
    {
        if (isset($data['username'])) unset($data['username']);
        if (isset($data['password'])) unset($data['password']);
        return $this->mapper->update($id, $this->handleData($data));
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
     * 用户退出
     */
    public function logout()
    {
        $user = nyuwa_user();
        $user->getJwt()->invalidToken();
    }

    /**
     * 获取用户信息
     * @return array
     */
    public function getInfo(): array
    {
//        $userId = (int)nyuwa_user()->getId();
//        $key = "loginInfo_userId_".$userId;
//        $val = Cache::get($key);
//        if (!$val){
//            $val = $this->getUserInfo(SystemUser::find($userId));
//            Cache::set($key,$val);
//        }
//        return $val;

        if ( ($uid = nyuwa_user()->getId()) ) {
            $key = "LOGIN_INFO_USER_ID_".$uid;
            $val = Cache::get($key);
            if (!$val){
                $val = $this->getUserInfo((int)$uid);
                Cache::set($key,$val);
            }
            return $val;
        }
        throw new NyuwaException(nyuwa_trans('system.unable_get_userinfo'), 500);
    }

    /**
     * 获取缓存用户信息
     * @param int $id
     * @return array
     */
    protected function getUserInfo(int $id): array
    {
        $user = $this->mapper->getModel()->find($id);
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
     */
    public function clearCache(string $id): bool
    {
        return Redis::del("LOGIN_INFO_USER_ID_{$id}") > 0;
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
        //判断旧密码是否正确
        $oldPassword = $params['oldPassword'];
        $model = $this->mapper->getModel()::find((int) nyuwa_user()->getId(), ['password']);
        if (! $this->mapper->checkPass($oldPassword, $model->password)) {
            return false;
        }
        return $this->mapper->initUserPassword((int)nyuwa_user()->getId(), $params['newPassword']);
    }


    /**
     * 获取在线用户
     * @param array $params
     * @return array
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
     */
    public function kickUser(string $id): bool
    {
        $prefix = config('cache.default.prefix');
        nyuwa_user()->getJwt()->invalidToken(Redis::get("{$prefix}Token:{$id}"));
        return Redis::del("{$prefix}Token:{$id}") > 0;
    }


}
