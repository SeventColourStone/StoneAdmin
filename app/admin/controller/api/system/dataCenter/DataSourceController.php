<?php


namespace app\admin\controller\api\system\dataCenter;


use app\admin\service\system\DataSourceService;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class DataSourceController extends NyuwaController
{


    /**
     * @Inject
     * @var DataSourceService
     */
    public $service;

    /**
     * @GetMapping("index")
     * @return NyuwaResponse
     * @Permission("system:dataMaintain:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * @GetMapping("detailed")
     * @return NyuwaResponse
     * @Permission("system:dataMaintain:detailed")
     */
    public function detailed(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getColumnList($request->input('table', null)));
    }

    /**
     * 优化表
     * @PostMapping("optimize")
     * @Permission("system:dataMaintain:optimize")
     * @OperationLog
     */
    public function optimize(Request $request): NyuwaResponse
    {
        $tables = $request->input('tables', []);
        return $this->success($this->service->optimize($tables));
    }

    /**
     * 清理表碎片
     * @PostMapping("fragment")
     * @Permission("system:dataMaintain:fragment")
     * @OperationLog
     */
    public function fragment(Request $request): NyuwaResponse
    {
        $tables = $request->input('tables', []);
        return $this->success($this->service->fragment($tables));
    }


}
