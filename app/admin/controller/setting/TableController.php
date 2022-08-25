<?php


namespace app\admin\controller\setting;


use app\admin\service\setting\TableService;
use app\admin\validate\setting\TableValidate;
use DI\Annotation\Inject;
use nyuwa\Nyuwa;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class TableController extends NyuwaController
{
    /**
     * @Inject
     * @var TableService
     */
    protected  $service;

    /**
     * @Inject
     * @var TableValidate 验证器
     */
    protected $validate;
    /**
     * @Inject 
     * @var Nyuwa
     */
    private $nyuwa;

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