<?php

declare(strict_types=1);
namespace app\admin\controller\api\system\userCenter;

use app\admin\service\system\SystemUserService;
use app\admin\validate\system\SystemUserValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * Class UserController
 * @package App\System\Controller
 * @Controller(prefix="system/user")
 * @Auth
 */
class UserController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemUserService
     */
    protected $service;

    /**
     * @Inject
     * @var SystemUserValidate
     */
    protected $validate;



    /**
     * @GetMapping("index")
     * @return NyuwaResponse
     * @Permission("system:user:index")
     */
    public function index(): NyuwaResponse
    {
        return $this->success($this->service->getPageList(nyuwa_request()->all()));
    }

    /**
     * @GetMapping("recycle")
     * @return NyuwaResponse
     * @Permission("system:user:recycle")
     */
    public function recycle(): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle(nyuwa_request()->all()));
    }

    /**
     * 新增一个用户
     * @PostMapping("save")
     * @param Request $request
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @Permission("system:user:save")
     * @OperationLog
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 获取一个用户信息
     * @GetMapping("read/{id}")
     * @param  $id
     * @return NyuwaResponse
     * @Permission("system:user:read")
     */
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->success($this->service->read($id));
    }

    /**
     * 更新一个用户信息
     * @PutMapping("update/{id}")
     * @param int $id
     * @param Request $request
     * @return NyuwaResponse
     * @Permission("system:user:update")
     * @OperationLog
     */
    public function update(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("update")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除用户到回收站
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:user:delete")
     * @OperationLog
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除用户 （清空回收站）
     * @DeleteMapping("realDelete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:user:realDelete")
     * @OperationLog
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->realDelete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的用户
     * @PutMapping("recovery/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:user:recovery")
     * @OperationLog
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

    /**
     * 更改用户状态
     * @PutMapping("changeStatus")
     * @param Request $request
     * @return NyuwaResponse
     * @Permission("system:user:changeStatus")
     * @OperationLog
     */
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus( $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }

    /**
     * 清除用户缓存
     * @PostMapping("clearCache")
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @Permission("system:user:cache")
     */
    public function clearCache(): NyuwaResponse
    {
        return $this->success($this->service->clearCache((string) nyuwa_request()->input('id', null)));
    }

    /**
     * 设置用户首页
     * @PostMapping("setHomePage")
     * @param Request $request
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @Permission("system:user:homePage")
     */
    public function setHomePage(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("homoPage")->check($request->all());
        return $this->service->setHomePage($data) ? $this->success() : $this->error();
    }

    /**
     * 初始化用户密码
     * @PutMapping("initUserPassword/{id}")
     * @param int $id
     * @return NyuwaResponse
     * @Permission("system:user:initUserPassword")
     * @OperationLog
     */
    public function initUserPassword(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->service->initUserPassword($id) ? $this->success() : $this->error();
    }

    /**
     * 更改用户资料，含修改头像 (不验证权限)
     * @PostMapping("updateInfo")
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function updateInfo(): NyuwaResponse
    {
        return $this->success($this->service->updateInfo(nyuwa_request()->all()));
    }

    /**
     * 修改密码 (不验证权限)
     * @PostMapping("modifyPassword")
     * @param SystemUserPasswordRequest $request
     * @return NyuwaResponse
     */
    public function modifyPassword(SystemUserPasswordRequest $request): NyuwaResponse
    {
        return $this->service->modifyPassword($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 用户导出
     * @PostMapping("export")
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Permission("system:user:export")
     * @return NyuwaResponse
     */
    public function export(): NyuwaResponse
    {
        return $this->service->export(nyuwa_request()->all(), \App\System\Dto\UserDto::class, '用户列表');
    }

    /**
     * 用户导入
     * @PostMapping("import")
     * @Permission("system:user:import")
     * @return NyuwaResponse
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function import(): NyuwaResponse
    {
        return $this->service->import(\App\System\Dto\UserDto::class) ? $this->success() : $this->error();
    }

    /**
     * 下载导入模板
     * @PostMapping("downloadTemplate")
     * @return NyuwaResponse
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadTemplate(): NyuwaResponse
    {
//        return (new NyuwaCollection)->export(\App\System\Dto\UserDto::class, '模板下载', []);
    }

    /**
     * 清除自己缓存
     * @PostMapping("clearSelfCache")
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function clearSelfCache(): NyuwaResponse
    {
        return $this->service->clearCache(user()->getId()) ? $this->success() : $this->error();
    }
}
