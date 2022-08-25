<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemUserService;
use app\admin\validate\system\SystemUserValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaCollection;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class UserController  extends NyuwaController
{

    /**
     * @Inject
     * @var SystemUserService
     */
    protected $service;

    /**
     * @Inject
     * @var SystemUserValidate 验证器
     */
    protected $validate;

    /**
     * 列表
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 回收站列表
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 新增
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 读取一条记录
     */
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->read($data["id"]));
    }

    /**
     * 更新一条记录
     */
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量回收一条记录
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除不回收记录
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的记录
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 更改用户状态
     */
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus($data["id"], $data['status'])
            ? $this->success() : $this->error();
    }

    /**
     * 清除用户缓存
     */
    #[PostMapping("clearCache"), Permission("system:user:cache")]
    public function clearCache(Request $request): NyuwaResponse
    {
        $this->service->clearCache((string) $request->input('id', null));
        return $this->success();
    }

    /**
     * 设置用户首页
     */
    #[PostMapping("setHomePage"), Permission("system:user:homePage")]
    public function setHomePage(SystemUserHompPageRequest $request): NyuwaResponse
    {
        return $this->service->setHomePage($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 初始化用户密码
     */
    #[PutMapping("initUserPassword/{id}"), Permission("system:user:initUserPassword"), OperationLog]
    public function initUserPassword(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->initUserPassword($data['id']) ? $this->success() : $this->error();
    }

    /**
     * 更改用户资料，含修改头像 (不验证权限)
     */
    #[PostMapping("updateInfo")]
    public function updateInfo(Request $request): NyuwaResponse
    {
        return $this->service->updateInfo($request->all()) ? $this->success() : $this->error();
    }

    /**
     * 修改密码 (不验证权限)
     */
    #[PostMapping("modifyPassword")]
    public function modifyPassword(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("modifyPassword")->check($request->all());
        return $this->service->modifyPassword($request->all()) ? $this->success() : $this->error(nyuwa_trans('system.valid_password'));
    }

    /**
     * 用户导出
     */
    #[PostMapping("export"), Permission("system:user:export"), OperationLog]
    public function export(Request $request): NyuwaResponse
    {
        return $this->service->export($request->all(), \App\System\Dto\UserDto::class, '用户列表');
    }

    /**
     */
    #[PostMapping("import"), Permission("system:user:import")]
    public function import(Request $request): NyuwaResponse
    {
        return $this->service->import(\App\System\Dto\UserDto::class) ? $this->success() : $this->error();
    }

    /**
     * 下载导入模板
     */
    #[PostMapping("downloadTemplate")]
    public function downloadTemplate(): NyuwaResponse
    {
        return (new NyuwaCollection)->export(\App\System\Dto\UserDto::class, '模板下载', []);
    }

    /**
     * 清除自己缓存
     */
    #[PostMapping("clearSelfCache")]
    public function clearSelfCache(): NyuwaResponse
    {
        return $this->service->clearCache(nyuwa_user()->getId()) ? $this->success() : $this->error();
    }


}