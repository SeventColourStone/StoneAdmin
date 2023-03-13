<?php


namespace plugin\stone\app\controller\system;


use DI\Attribute\Inject;
use plugin\stone\app\service\setting\RelyMonitorService;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class RelyController extends NyuwaController
{

    #[Inject]
    protected RelyMonitorService $service;


    /**
     * 获取依赖包列表数据
     */
    #[GetMapping("index"), Permission("system:monitor:rely")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPackagePageList($request->all()));
    }

    /**
     * 获取依赖包详细信息
     */
    #[GetMapping("detail"), Permission("system:monitor:relyDetail")]
    public function detail(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPackageDetail($request->input('name', '')));
    }
}