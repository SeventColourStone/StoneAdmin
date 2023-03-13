<?php


namespace plugin\stone\app\controller\system;


use plugin\stone\app\service\setting\DataSourceService;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class DataMaintainController extends NyuwaController
{

    /**
     * @var DataSourceService
     */
    #[Inject]
    public DataSourceService $service;

    /**
     * @return NyuwaResponse
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * @return NyuwaResponse
     */
    public function detailed(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getColumnList($request->input('table', null)));
    }

    /**
     * 优化表
     */
    public function optimize(Request $request): NyuwaResponse
    {
        $tables = $request->input('tables', []);
        return $this->success($this->service->optimize($tables));
    }

    /**
     * 清理表碎片
     */
    public function fragment(Request $request): NyuwaResponse
    {
        $tables = $request->input('tables', []);
        return $this->success($this->service->fragment($tables));
    }

}