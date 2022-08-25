<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemNoticeService;
use app\admin\validate\system\SystemNoticeValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class NoticeController extends NyuwaController
{
    /**
     * SystemNoticeService 服务
     * @Inject
     * @var SystemNoticeService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemNoticeValidate 验证器
     */
    protected $validate;


    /**
     * 列表
     */
    #[GetMapping("index"), Permission("system:notice:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 回收站列表
     */
    #[GetMapping("recycle"), Permission("system:notice:recycle")]
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 新增
     */
    #[PostMapping("save"), Permission("system:notice:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 读取数据
     */
    #[GetMapping("read/{id}"), Permission("system:notice:read")]
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->read($data["id"]));
    }

    /**
     * 更新
     * @param int $idd
     */
    #[PutMapping("update/{id}"), Permission("system:notice:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站
     */
    #[DeleteMapping("delete/{ids}"), Permission("system:notice:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除数据 （清空回收站）
     */
    #[DeleteMapping("realDelete/{ids}"), Permission("system:notice:realDelete"), OperationLog]
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的数据
     */
    #[PutMapping("recovery/{ids}"), Permission("system:notice:recovery")]
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }

}