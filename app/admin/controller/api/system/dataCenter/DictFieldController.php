<?php


namespace app\admin\controller\api\system\dataCenter;


use app\admin\service\system\SystemDictFieldService;
use app\admin\validate\system\DictFieldValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * 字典表字段控制器
 * Class FieldController
 * @package app\admin\controller\api\system\dataCenter
 */
class DictFieldController extends NyuwaController
{
    /**
     * 字典类型服务
     * @Inject
     * @var SystemDictFieldService
     */
    protected $service;

    /**
     * @Inject
     * @var DictFieldValidate
     */
    protected $validate;
    /**
     * 获取字段典
     * @GetMapping("index")
     * @return NyuwaResponse
     * @Permission("system:dictField:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 获取字段典回收站
     * @GetMapping("recycle")
     * @return NyuwaResponse
     * @Permission("system:dictField:recycle")
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 新增字段典
     * @PostMapping("save")
     * @param DictFieldCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:dictField:save")
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
     * @Permission("system:dictField:read")
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
     * @param DictFieldCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:dictField:update")
     */
    public function update(Request $request): NyuwaResponse
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
     * @Permission("system:dictField:delete")
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
     * @Permission("system:dictField:realDelete")
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
     * @Permission("system:dictField:recovery")
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

}
