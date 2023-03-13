<?php


namespace plugin\stone\app\controller\system;


use plugin\stone\app\service\setting\CacheMonitorService;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class CacheController extends NyuwaController
{

    /**
     * @var CacheMonitorService
     */
    #[Inject]
    protected CacheMonitorService $service;

    /**
     * 获取Redis服务器信息
     */
    #[GetMapping("monitor"), Permission("system:cache:monitor")]
    public function monitor(): NyuwaResponse
    {
        return $this->success($this->service->getCacheServerInfo());
    }

    /**
     * 查看key内容
     * @return NyuwaResponse
     */
    #[PostMapping("view")]
    public function view(Request $request): NyuwaResponse
    {
        return $this->success(['content' => $this->service->view($request->input('key'))]);
    }

    /**
     * 删除一个缓存
     */
    #[DeleteMapping("delete"), Permission("system:cache:delete"), OperationLog]
    public function delete(Request $request): NyuwaResponse
    {
        return $this->service->delete($request->input('key', null))
            ? $this->success()
            : $this->error();
    }

    /**
     * 清空所有缓存
     * @return NyuwaResponse
     */
    #[DeleteMapping("clear"), Permission("system:cache:clear"), OperationLog]
    public function clear(): NyuwaResponse
    {
        return $this->service->clear() ? $this->success() : $this->error();
    }
}