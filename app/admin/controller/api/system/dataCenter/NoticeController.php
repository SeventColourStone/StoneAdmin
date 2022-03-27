<?php


namespace app\admin\controller\api\system\dataCenter;


use app\admin\service\system\SystemNoticeService;
use app\admin\validate\system\SystemNoticeValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class NoticeController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemNoticeService
     */
    protected $service;

    /**
     * @Inject
     * @var SystemNoticeValidate
     */
    protected $validate;

    /**
     * 列表
     * @GetMapping("index")
     * @return NyuwaResponse
     * @Permission("system:notice:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 回收站列表
     * @GetMapping("recycle")
     * @return NyuwaResponse
     * @Permission("system:notice:recycle")
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 新增
     * @PostMapping("save")
     * @param SystemNoticeCreateRequest $request
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Throwable
     * @Permission("system:notice:save")
     * @OperationLog
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 读取数据
     * @GetMapping("read/{id}")
     * @param int $id
     * @return NyuwaResponse
     * @Permission("system:notice:read")
     */
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->success($this->service->read($id));
    }

    /**
     * 更新
     * @PutMapping("update/{id}")
     * @param int $id
     * @param SystemNoticeUpdateRequest $request
     * @return NyuwaResponse
     * @Permission("system:notice:update")
     * @OperationLog
     */
    public function update(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("update")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:notice:delete")
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
     * @Permission("system:notice:realDelete")
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
     * @Permission("system:notice:recovery")
     * @OperationLog
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

}
