<?php


namespace plugin\stone\app\controller\system;


use plugin\stone\app\service\system\SystemDeptService;
use plugin\stone\app\validate\system\SystemDeptValidate;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class DeptController extends NyuwaController
{
    /**
     * @var SystemDeptService
     */
    #[Inject]
    protected SystemDeptService $service;


    /**
     * @var SystemDeptValidate 验证器
     */
    #[Inject]
    protected SystemDeptValidate $validate;

    /**
     * 部门树列表
     */
    #[GetMapping("index"), Permission("system:dept:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getTreeList($request->all()));
    }

    /**
     * 回收站部门树列表
     */
    #[GetMapping("recycle"), Permission("system:dept:recycle")]
    public function recycle(Request $request):NyuwaResponse
    {
        return $this->success($this->service->getTreeListByRecycle($request->all()));
    }

    /**
     * 前端选择树（不需要权限）
     */
    #[GetMapping("tree")]
    public function tree(): NyuwaResponse
    {
        return $this->success($this->service->getSelectTree());
    }

    /**
     * 新增部门
     */
    #[PostMapping("save"), Permission("system:dept:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($request->all())]);
    }

    /**
     * 更新部门
     */
    #[PutMapping("update/{id}"), Permission("system:dept:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除部门到回收站
     */
    #[DeleteMapping("delete/{ids}"), Permission("system:dept:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除部门 （清空回收站）
     */
    #[DeleteMapping("realDelete/{ids}"), Permission("system:dept:realDelete"), OperationLog]
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $data = $this->service->realDel($data["id"]) ? $this->success() : $this->error();
        return is_null($data) ?
            $this->success() :
            $this->success(nyuwa_trans('system.exists_children_ctu', ['names' => implode(',', $data)]));
    }

    /**
     * 单个或批量恢复在回收站的部门
     */
    #[PutMapping("recovery/{ids}"), Permission("system:dept:recovery")]
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 更改部门状态
     */
    #[PutMapping("changeStatus"), Permission("system:dept:changeStatus"), OperationLog]
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus( $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }
}