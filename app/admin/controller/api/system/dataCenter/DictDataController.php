<?php


namespace app\admin\controller\api\system\dataCenter;


use app\admin\service\system\SystemDictDataService;
use app\admin\validate\system\DictDataValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * 字典类型控制器
 * Class DictDataController
 * @package app\admin\controller\api\system\dataCenter
 */
class DictDataController extends NyuwaController
{

    /**
     * 字典数据服务
     * @Inject
     * @var SystemDictDataService
     */
    protected $service;

    /**
     * @Inject
     * @var DictDataValidate
     */
    protected $validate;
    /**
     * @GetMapping("index")
     * @return NyuwaResponse
     * @Permission("system:dataDict:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 快捷查询一个字典
     * @GetMapping("list")
     * @return NyuwaResponse
     */
    public function list(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getList($request->all()));
    }

    /**
     * 快捷查询多个字典
     * @GetMapping("lists")
     * @return NyuwaResponse
     */
    public function lists(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getLists($request->all()));
    }

    /**
     * @PostMapping("clearCache")
     * @Permission("system:dataDict:clearCache")
     * @OperationLog
     * @return NyuwaResponse
     */
    public function clearCache(Request $request): NyuwaResponse
    {
        return $this->service->clearCache() ? $this->success() : $this->error();
    }

    /**
     * @GetMapping("recycle")
     * @return NyuwaResponse
     * @Permission("system:dataDict:recycle")
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 新增字典类型
     * @PostMapping("save")
     * @param DictDataCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:dataDict:save")
     * @OperationLog
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 获取一个字典类型数据
     * @GetMapping("read/{id}")
     * @param int $id
     * @return NyuwaResponse
     * @Permission("system:dataDict:read")
     */
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->success($this->service->read($id));
    }

    /**
     * 更新一个字典类型
     * @PutMapping("update/{id}")
     * @param int $id
     * @param DictDataCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:dataDict:update")
     */
    public function update(Request $request,int $id): NyuwaResponse
    {
        $data = $this->validate->scene("update")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量字典数据
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dataDict:delete")
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除字典 （清空回收站）
     * @DeleteMapping("realDelete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dataDict:realDelete")
     * @OperationLog
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->realDelete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的字典
     * @PutMapping("recovery/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dataDict:recovery")
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

    /**
     * 更改字典状态
     * @PutMapping("changeStatus")
     * @param DictDataStatusRequest $request
     * @return NyuwaResponse
     * @Permission("system:dataDict:changeStatus")
     * @OperationLog
     */
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus((int) $request->input('id'), (string) $request->input('status'))
            ? $this->success() : $this->error();
    }

}
