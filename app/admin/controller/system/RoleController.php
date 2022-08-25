<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemRoleService;
use app\admin\validate\system\SystemRoleValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class RoleController extends NyuwaController
{

    /**
     * SystemRoleService 服务
     * @Inject
     * @var SystemRoleService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemRoleValidate 验证器
     */
    protected $validate;


    /**
     * 角色分页列表
     */
    #[GetMapping("index"), Permission("system:role:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 回收站角色分页列表
     */
    #[GetMapping("recycle"), Permission("system:role:recycle")]
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 通过角色获取菜单
     */
    #[GetMapping("getMenuByRole/{id}")]
    public function getMenuByRole(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->getMenuByRole($data["id"]));
    }

    /**
     * 通过角色获取部门
     */
    #[GetMapping("getDeptByRole/{id}")]
    public function getDeptByRole(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->getDeptByRole($data["id"]));
    }

    /**
     * 获取角色列表 (不验证权限)
     */
    #[GetMapping("list")]
    public function list(): NyuwaResponse
    {
        return $this->success($this->service->getList());
    }

    /**
     * 新增角色
     */
    #[PostMapping("save"), Permission("system:role:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 更新角色
     */
    #[PutMapping("update/{id}"), Permission("system:role:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 更新用户菜单权限
     */
    #[PutMapping("menuPermission/{id}"), Permission("system:role:menuPermission"), OperationLog]
    public function menuPermission(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 更新用户数据权限
     */
    #[PutMapping("dataPermission/{id}"), Permission("system:role:dataPermission"), OperationLog]
    public function dataPermission(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站
     */
    #[DeleteMapping("delete/{ids}"), Permission("system:role:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除数据 （清空回收站）
     */
    #[DeleteMapping("realDelete/{ids}"), Permission("system:role:realDelete"), OperationLog]
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的数据
     */
    #[PutMapping("recovery/{ids}"), Permission("system:role:recovery")]
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 更改角色状态
     */
    #[PutMapping("changeStatus"), Permission("system:role:changeStatus"), OperationLog]
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus( $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }
}