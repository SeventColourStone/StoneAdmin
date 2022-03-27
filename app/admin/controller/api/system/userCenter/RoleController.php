<?php

declare(strict_types=1);
namespace app\admin\controller\api\system\userCenter;

use app\admin\service\system\SystemRoleService;
use app\admin\validate\system\SystemRoleValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * Class RoleController
 * @package App\System\Controller
 * @Controller(prefix="system/role")
 * @Auth
 */
class RoleController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemRoleService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemRoleValidate
     */
    protected $validate;

    /**
     * 获取角色分页列表
     * @GetMapping("index")
     * @Permission("system:role:index")
     * @return NyuwaResponse
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * @GetMapping("recycle")
     * @return NyuwaResponse
     * @Permission("system:role:recycle")
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 通过角色获取菜单
     * @GetMapping("getMenuByRole/{id}")
     * @param int $id
     * @return NyuwaResponse
     */
    public function getMenuByRole(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->success($this->service->getMenuByRole($id));
    }

    /**
     * 通过角色获取部门
     * @GetMapping("getDeptByRole/{id}")
     * @param int $id
     * @return NyuwaResponse
     */
    public function getDeptByRole(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->success($this->service->getDeptByRole($id));
    }

    /**
     * 获取角色列表
     * @GetMapping("list")
     * @return NyuwaResponse
     */
    public function list(): NyuwaResponse
    {
        return $this->success($this->service->getList());
    }

    /**
     * 新增角色
     * @PostMapping("save")
     * @param Request $request
     * @return NyuwaResponse
     * @Permission("system:role:save")
     * @OperationLog
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 更新角色
     * @PutMapping("update/{id}")
     * @param int $id
     * @param Request $request
     * @return NyuwaResponse
     * @Permission("system:role:update")
     * @OperationLog
     */
    public function update(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("update")->check($request->all());
        $id = $data['id'];
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 更新用户菜单权限
     * @PutMapping("menuPermission/{id}")
     * @param int $id
     * @return NyuwaResponse
     * @Permission("system:role:menuPermission")
     * @OperationLog
     */
    public function menuPermission(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->service->update((string)$id, $request->all()) ? $this->success() : $this->error();
    }

    /**
     * 更新用户数据权限
     * @PutMapping("dataPermission/{id}")
     * @param int $id
     * @return NyuwaResponse
     * @Permission("system:role:dataPermission")
     * @OperationLog
     */
    public function dataPermission(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $request->all()) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:role:delete")
     * @OperationLog
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除数据 （清空回收站）
     * @DeleteMapping("realDelete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:role:realDelete")
     * @OperationLog
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->realDelete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的数据
     * @PutMapping("recovery/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:role:recovery")
     * @OperationLog
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

    /**
     * 更改角色状态
     * @PutMapping("changeStatus")
     * @param Request $request
     * @return NyuwaResponse
     * @Permission("system:role:changeStatus")
     * @OperationLog
     */
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus((int) $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }
}
