<?php


namespace app\admin\controller\setting;


use app\admin\service\setting\SettingCrontabLogService;
use app\admin\service\setting\SettingCrontabService;
use app\admin\validate\setting\SettingCrontabValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class CrontabController extends NyuwaController
{
    /**
     * SettingCrontabService 服务
     * @Inject
     * @var SettingCrontabService
     */
    protected $service;

    /**
     * @Inject
     * @var SettingCrontabLogService
     */
    protected $logService;


    /**
     * @Inject
     * @var SettingCrontabValidate 验证器
     */
    protected $validate;


    /**
     * 获取列表分页数据
     */
    #[GetMapping("index"), Permission("setting:crontab:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getList($request->all()));
    }

    /**
     * 获取日志列表分页数据
     */
    #[GetMapping("logPageList")]
    public function logPageList(Request $request): NyuwaResponse
    {
        return $this->success($this->logService->getPageList($request->all()));
    }

    /**
     * 保存数据
     */
    #[PostMapping("save"), Permission("setting:crontab:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 立即执行定时任务
     */
    #[PostMapping("run"), Permission("setting:crontab:run"), OperationLog]
    public function run(Request $request): NyuwaResponse
    {
        $id = $request->input('id', null);
        if (is_null($id)) {
            return $this->error();
        } else {
            return $this->service->run($id) ? $this->success() : $this->error();
        }
    }

    /**
     * 获取一条数据信息
     */
    #[GetMapping("read/{id}"), Permission("setting:crontab:read")]
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->read($data['id']));
    }

    /**
     * 更新数据
     */
    #[PutMapping("update/{id}"), Permission("setting:crontab:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除
     */
    #[DeleteMapping("delete/{ids}"), Permission("setting:crontab:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data['id']) ? $this->success() : $this->error();
    }

    /**
     * 删除定时任务日志
     */
    #[DeleteMapping("deleteCrontabLog/{ids}"), Permission("setting:crontab:deleteCrontabLog"), OperationLog]
    public function deleteCrontabLog(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->logService->realDelete($data['id']) ? $this->success() : $this->error();
    }

}