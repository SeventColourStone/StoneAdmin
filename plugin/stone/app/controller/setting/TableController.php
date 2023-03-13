<?php


namespace plugin\stone\app\controller\setting;


use plugin\stone\app\service\setting\TableService;
use plugin\stone\app\validate\setting\TableValidate;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\Nyuwa;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class TableController extends NyuwaController
{
    /**
     * @var TableService
     */
    #[Inject]
    protected TableService  $service;

    /**
     * @var TableValidate 验证器
     */
    #[Inject]
    protected TableValidate $validate;
    /**
     * @var Nyuwa
     */
    #[Inject]
    private Nyuwa $nyuwa;

    /**
     * 获取系统信息
     * @return NyuwaResponse
     */
    #[GetMapping("getSystemInfo")]
    public function getSystemInfo(): NyuwaResponse
    {
        return $this->success([
            'tablePrefix' => $this->service->getTablePrefix(),
            'modulesList' => $this->nyuwa->getModuleInfo()
        ]);
    }

    /**
     * 创建数据表
     * @return NyuwaResponse
     */
    #[PutMapping("save"), Permission("setting:table:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("tableCreate")->check($request->all());
        if ($this->service->createTable($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}