<?php


namespace plugin\stone\app\controller\system;


use plugin\stone\app\service\system\SystemDictTypeService;
use plugin\stone\app\validate\system\SystemDictTypeValidate;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class DictTypeController extends NyuwaController
{
    /**
     * SystemDictTypeService 服务
     * @var SystemDictTypeService
     */
    #[Inject]
    protected SystemDictTypeService $service;


    /**
     * @var SystemDictTypeValidate 验证器
     */
    #[Inject]
    protected SystemDictTypeValidate $validate;


    /**
     * 获取字典，无分页
     */
    #[GetMapping("index"), Permission("system:dictType:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getList($request->all()));
    }

    /**
     * 回收站列表
     */
    #[GetMapping("recycle"), Permission("system:dictType:recycle")]
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getListByRecycle($request->all()));
    }

    /**
     * 新增字典类型
     */
    #[PostMapping("save"), Permission("system:dictType:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 获取一个字典类型数据
     */
    #[GetMapping("read/{id}"), Permission("system:dictType:read")]
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->read($data["id"]));
    }

    /**
     * 更新一个字典类型
     */
    #[PutMapping("update/{id}"), Permission("system:dictType:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量字典数据
     */
    #[DeleteMapping("delete/{ids}"), Permission("system:dictType:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除字典数据 （清空回收站）
     */
    #[DeleteMapping("realDelete/{ids}"), Permission("system:dictType:realDelete"), OperationLog]
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的用户
     */
    #[PutMapping("recovery/{ids}"), Permission("system:dictType:recovery")]
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }
    
}