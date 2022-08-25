<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemDictDataService;
use app\admin\validate\system\SystemDictDataValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class DataDictController extends NyuwaController
{

    /**
     * SystemDictDataService 服务
     * @Inject
     * @var SystemDictDataService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemDictDataValidate 验证器
     */
    protected $validate;


    /**
     * 列表
     */
    #[GetMapping("index"), Permission("system:dataDict:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 快捷查询一个字典
     */
    #[GetMapping("list")]
    public function list(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getList($request->all()));
    }

    /**
     * 快捷查询多个字典
     */
    #[GetMapping("lists")]
    public function lists(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getLists($request->all()));
    }

    /**
     * 清除字典缓存
     */
    #[PostMapping("clearCache"), Permission("system:dataDict:clearCache"), OperationLog]
    public function clearCache(Request $request): NyuwaResponse
    {
        return $this->service->clearCache() ? $this->success() : $this->error();
    }

    /**
     * 回收站列表
     */
    #[GetMapping("recycle"), Permission("system:dataDict:recycle")]
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 新增字典类型
     */
    #[PostMapping("save"), Permission("system:dataDict:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 获取一个字典类型数据
     */
    #[GetMapping("read/{id}"), Permission("system:dataDict:read")]
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->read($data["id"]));
    }

    /**
     * 更新一个字典类型
     */
    #[PutMapping("update/{id}"), Permission("system:dataDict:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量字典数据
     */
    #[DeleteMapping("delete/{ids}"), Permission("system:dataDict:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除字典 （清空回收站）
     */
    #[DeleteMapping("realDelete/{ids}"), Permission("system:dataDict:realDelete"), OperationLog]
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的字典
     */
    #[PutMapping("recovery/{ids}"), Permission("system:dataDict:recovery")]
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 更改字典状态
     * @param DictDataStatusRequest $request
     */
    #[PutMapping("changeStatus"), Permission("system:dataDict:changeStatus"), OperationLog]
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus( $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }

}