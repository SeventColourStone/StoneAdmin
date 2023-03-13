<?php


namespace plugin\stone\app\controller\setting;


use plugin\stone\app\service\setting\SettingGenerateColumnsService;
use plugin\stone\app\service\setting\SettingGenerateTablesService;
use plugin\stone\app\validate\setting\SettingGenerateColumnsValidate;
use plugin\stone\app\validate\setting\SettingGenerateTablesValidate;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Log;
use support\Request;

class CodeController extends NyuwaController
{

    /**
     * 信息表服务
     * @var SettingGenerateTablesService
     */
    #[Inject]
    protected SettingGenerateTablesService $tableService;

    /**
     * 信息字段表服务
     * @var SettingGenerateColumnsService
     */
    #[Inject]
    protected SettingGenerateColumnsService $columnService;


    /**
     * 信息字段验证器
     * @var SettingGenerateColumnsValidate
     */
    #[Inject]
    protected SettingGenerateColumnsValidate $columnValidate;


    /**
     * 信息表验证器
     * @var SettingGenerateTablesValidate
     */
    #[Inject]
    protected SettingGenerateTablesValidate $tableValidate;


    /**
     * 代码生成列表分页
     */
    #[GetMapping("index"), Permission("setting:code")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->tableService->getPageList($request->All()));
    }

    /**
     * 获取业务表字段信息
     */
    #[GetMapping("getTableColumns")]
    public function getTableColumns(Request $request): NyuwaResponse
    {
        return $this->success($this->columnService->getList($request->all()));
    }

    /**
     * 预览代码
     */
    #[GetMapping("preview"), Permission("setting:code:preview")]
    public function preview(Request $request): NyuwaResponse
    {
        return $this->success($this->tableService->preview((int) $request->input('id', 0)));
    }

    /**
     * 读取表数据
     */
    #[GetMapping("readTable")]
    public function readTable(Request $request): NyuwaResponse
    {
        return $this->success($this->tableService->read((int) $request->input('id')));
    }

    /**
     * 更新业务表信息
     */
    #[PostMapping("update"), Permission("setting:code:update")]
    public function update(Request $request): NyuwaResponse
    {
        $this->tableValidate->scene("generate_update")->check($request->all());
        $data = $request->all();
        $bool = $this->tableService->updateTableAndColumns($data);
        Log::info("处理完毕.正在http返回数据：".$bool);
        return $bool ? $this->success() : $this->error();
    }

    /**
     * 生成代码
     */
    #[PostMapping("generate/{ids}"), Permission("setting:code:generate"), OperationLog]
    public function generate(Request $request): NyuwaResponse
    {
        $data = $this->tableValidate->scene("key")->check($request->all());
        return $this->_download($this->tableService->generate($data["id"]), 'mineadmin.zip');
    }

    /**
     * 加载数据表
     */
    #[PostMapping("loadTable"), Permission("setting:code:loadTable"), OperationLog]
    public function loadTable(Request $request): NyuwaResponse
    {
        $data = $this->tableValidate->scene("load_table")->check($request->all());
        return !empty($this->tableService->loadTable($request->input('names'))) ? $this->success() : $this->error();
    }

    /**
     * 删除代码生成表
     */
    #[DeleteMapping("delete/{ids}"), Permission("setting:code:delete"), OperationLog]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->tableValidate->scene("key")->check($request->all());
        return $this->tableService->delete($data['id']) ? $this->success() : $this->error();
    }

    /**
     * 同步数据库中的表信息跟字段
     */
    #[PutMapping("sync/{id}"), Permission("setting:code:sync"), OperationLog]
    public function sync(Request $request): NyuwaResponse
    {
        $data = $this->tableValidate->scene("key")->check($request->all());
        return $this->tableService->sync($data['id']) ? $this->success() : $this->error();
    }

    /**
     * 获取所有启用状态模块下的所有模型
     */
    #[GetMapping("getModels")]
    public function getModels(): NyuwaResponse
    {
        return $this->success($this->tableService->getModels());
    }
}