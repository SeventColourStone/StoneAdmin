<?php


namespace app\admin\controller\api\system\dataCenter;


use app\admin\service\system\SystemDictTypeService;
use app\admin\validate\system\DictTypeValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * 字典类型控制器
 * Class DictTypeController
 * @package app\admin\controller\api\system\dataCenter
 */
class DictTypeController extends NyuwaController
{
    /**
     * 字典类型服务
     * @Inject
     * @var SystemDictTypeService
     */
    protected $service;

    /**
     * @Inject
     * @var DictTypeValidate
     */
    protected $validate;
    /**
     * 获取字典
     * @GetMapping("index")
     * @return NyuwaResponse
     * @Permission("system:dictType:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * @GetMapping("recycle")
     * @return NyuwaResponse
     * @Permission("system:dictType:recycle")
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 新增字典类型
     * @PostMapping("save")
     * @param DictTypeCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:dictType:save")
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
     * @Permission("system:dictType:read")
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
     * @param DictTypeCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:dictType:update")
     */
    public function update(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量字典数据
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dictType:delete")
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除用户 （清空回收站）
     * @DeleteMapping("realDelete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dictType:realDelete")
     * @OperationLog
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->realDelete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的用户
     * @PutMapping("recovery/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:dictType:recovery")
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

}
