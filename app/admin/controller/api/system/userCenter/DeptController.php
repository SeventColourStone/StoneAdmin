<?php

declare(strict_types=1);
namespace app\admin\controller\api\system\userCenter;


use app\admin\service\system\SystemDeptService;
use app\admin\validate\system\SystemDeptValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * Class DeptController
 * @package App\System\Controller
 * @Controller(prefix="system/dept")
 * @Auth
 */
class DeptController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemDeptService
     */
    protected $service;

    /**
     * @Inject
     * @var SystemDeptValidate
     */
    protected $validate;

    /**
     * 获取部门树
     * @GetMapping("index")
     * @Permission("system:dept:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getTreeList($request->all()));
    }

    /**
     * 从回收站获取部门树
     * @GetMapping("recycle")
     * @Permission("system:dept:recycle")
     */
    public function recycleTree(Request $request):NyuwaResponse
    {
        return $this->success($this->service->getTreeListByRecycle($request->all()));
    }

    /**
     * 前端选择树（不需要权限）
     * @GetMapping("tree")
     */
    public function tree(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getSelectTree());
    }

    /**
     * 新增部门
     * @PostMapping("save")
     * @param SystemDeptCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:dept:save")
     * @OperationLog
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 更新部门
     * @PutMapping("update/{id}")
     * @Permission("system:dept:update")
     * @param int $id
     * @param SystemDeptCreateRequest $request
     * @OperationLog
     * @return NyuwaResponse
     */
    public function update(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除部门到回收站
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dept:delete")
     * @OperationLog
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除部门 （清空回收站）
     * @DeleteMapping("realDelete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dept:realDelete")
     * @OperationLog
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        $data = $this->service->realDel($ids);
        return is_null($data) ?
            $this->success() :
            $this->success(nyuwa_trans('system.exists_children_ctu', ['names' => implode(',', $data)]));
    }

    /**
     * 单个或批量恢复在回收站的部门
     * @PutMapping("recovery/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dept:recovery")
     * @OperationLog
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

    /**
     * 更改部门状态
     * @PutMapping("changeStatus")
     * @param SystemDeptStatusRequest $request
     * @return NyuwaResponse
     * @Permission("system:dept:changeStatus")
     * @OperationLog
     */
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus($data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }
}
