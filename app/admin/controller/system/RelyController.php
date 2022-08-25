<?php


namespace app\admin\controller\system;


use app\admin\service\setting\RelyMonitorService;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class RelyController extends NyuwaController
{

    /**
     * RelyMonitorService 服务
     * @Inject
     * @var RelyMonitorService
     */
    protected $service;


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