<?php

declare(strict_types=1);

namespace app\admin\service\system;


use app\admin\mapper\system\SystemMenuMapper;
use app\admin\model\system\SystemMenu;
use app\admin\model\system\SystemUser;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemMenuService extends AbstractService
{
    /**
     * @Inject
     * @var SystemMenuMapper
     */
    public $mapper;


    /**
     * @Inject
     * @var SystemRoleService
     */
    protected $sysRoleService;


    /**
     * @param array|null $params
     * @return array
     */
    public function getTreeList(?array $params = null): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'desc'], $params);
        return parent::getTreeList($params);
    }

    /**
     * @param array|null $params
     * @return array
     */
    public function getTreeListByRecycle(?array $params = null): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'asc'], $params);
        return parent::getTreeListByRecycle($params);
    }

    /**
     * 获取前端选择树
     * @return array
     */
    public function getSelectTree(): array
    {
        return $this->mapper->getSelectTree();
    }

    /**
     * 通过code获取菜单名称
     * @param string $code
     * @return string
     */
    public function findNameByCode(string $code): string
    {
        if (strlen($code) < 1) {
            return nyuwa_trans('system.undefined_menu');
        }
        $name = $this->mapper->findNameByCode($code);
        return $name ?? nyuwa_trans('system.undefined_menu');
    }

    /**
     * 新增菜单
     * @param array $data
     * @return string
     */
    public function save(array $data): string
    {
        $id = $this->mapper->save($this->handleData($data));

        // 生成RESTFUL按钮菜单
        if ($data['type'] == SystemMenu::MENUS_LIST && $data['restful'] == '0') {
            $model = $this->mapper->model::find($id, ['id', 'name', 'code']);
            $this->genButtonMenu($model);
        }

        return $id;
    }


    /**
     * 生成按钮菜单
     * @param SystemMenu $model
     * @return bool
     */
    public function genButtonMenu(SystemMenu $model): bool
    {
        $buttonMenus = [
            ['name' => $model->name.'列表', 'code' => $model->code.':index'],
            ['name' => $model->name.'回收站', 'code' => $model->code.':recycle'],
            ['name' => $model->name.'保存', 'code' => $model->code.':save'],
            ['name' => $model->name.'更新', 'code' => $model->code.':update'],
            ['name' => $model->name.'删除', 'code' => $model->code.':delete'],
            ['name' => $model->name.'读取', 'code' => $model->code.':read'],
            ['name' => $model->name.'恢复', 'code' => $model->code.':recovery'],
            ['name' => $model->name.'真实删除', 'code' => $model->code.':realDelete'],
            ['name' => $model->name.'导入', 'code' => $model->code.':import'],
            ['name' => $model->name.'导出', 'code' => $model->code.':export']
        ];

        foreach ($buttonMenus as $button) {
            $this->save(
                array_merge(
                    ['parent_id' => $model->id, 'type' => SystemMenu::BUTTON],
                    $button
                )
            );
        }

        return true;
    }

    /**
     * 更新菜单
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        return $this->mapper->update($id, $this->handleData($data));
    }

    /**
     * 处理数据
     * @param $data
     * @return mixed
     */
    protected function handleData($data) {
        if (isset($data['parent_id'])){
            if ($data['parent_id'] == 0) {
                $data['level'] = '0';
                $data['type'] = SystemMenu::DIRECTORY_LIST;
            } else {
                if (is_array($data['parent_id'])) {
                    $data['parent_id'] = array_pop($data['parent_id']);
                }
                $parentMenu = $this->mapper->read((string)$data['parent_id']);
                $data['level'] = $parentMenu['level'] . ',' . $parentMenu['id'];
            }
        }
        return $data;
    }

    /**
     * 真实删除菜单
     * @param string $ids
     * @return array
     */
    public function realDel(string $ids): ?array
    {
        $ids = explode(',', $ids);
        // 跳过的菜单
        $ctuIds = [];
        if (count($ids)) foreach ($ids as $id) {
            if (!$this->checkChildrenExists($id)) {
                $this->mapper->realDelete([$id]);
            } else {
                array_push($ctuIds, $id);
            }
        }
        return count($ctuIds) ? $this->mapper->getMenuName($ctuIds) : null;
    }

    /**
     * 检查子菜单是否存在
     * @param int $id
     * @return bool
     */
    public function checkChildrenExists(string $id): bool
    {
        return $this->mapper->checkChildrenExists($id);
    }

    /**
     * 过滤通过角色查询出来的菜单id列表，并去重
     * @param array $roleData
     * @return array
     */
    public function filterMenuIds(array &$roleData): array
    {
        $ids = [];
        foreach ($roleData as $roleDatum) {
            foreach ($roleDatum['menus'] as $menu) {
                $ids[] = $menu['id'];
            }
        }
        unset($roleData);
        return array_unique($ids);
    }

    /**
     * 获取用户菜单树
     */
    public function userMenu($params){

        $loginUser = nyuwa_user();
        if ($loginUser->isSuperAdmin()) {
            //全部路由绿灯
            return $this->mapper->getSuperAdminMenu();
        } else {
            $userId = $loginUser->getId();
            $systemUser = SystemUser::query()->find($userId);
            $roles = $this->sysRoleService->mapper->getMenuIdsByRoleIds($systemUser->roles()->pluck('id')->toArray());
            $ids = $this->filterMenuIds($roles);
            return $this->mapper->getMenuByIds($ids);
        }
    }

    /**
     * 获取用户菜单树
     */
    public function userMenu1(){
//        $data['routers'] = $this->mapper->getSuperAdminRouters();
        return $this->mapper->getSuperAdminMenu();
//        $userId = (int)nyuwa_user()->getId();
//        SystemUser::find($userId);
//        $this->getTreeList();
//        $user = nyuwa_user();
//        if ($user->isSuperAdmin()) {
//            //全部路由绿灯
//            $data['routers'] = $this->mapper->getSuperAdminRouters();
//        } else {
//            $roles = $this->sysRoleService->mapper->getMenuIdsByRoleIds($user->roles()->pluck('id')->toArray());
//            $ids = $this->sysUserService->filterMenuIds($roles);
//            $data['roles'] = $user->roles()->pluck('code')->toArray();
//            $data['routers'] = $this->mapper->getRoutersByIds($ids);
//            $data['codes'] = $this->mapper->getMenuCode($ids);
//            $select = "id,parent_id,level,name as title,icon,redirect as href,type,open_type as openType";
//            if (isset($params['select'])){
//                $select = $params['select'];
//            }
//            $params = array_merge(['orderBy' => 'sort', 'orderType' => 'asc','select' =>$select], $params);
//            return parent::getTreeList($params);
//        }
    }

}
