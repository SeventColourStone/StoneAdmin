<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemUserService;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class OnlineUserController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemUserService
     */
    protected $service;

    /**
     * 获取在线用户列表
     */
    #[GetMapping("index"), Permission("system:onlineUser:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getOnlineUserPageList($request->all()));
    }

    /**
     * 强退用户
     */
    #[PostMapping("kick"), Permission("system:onlineUser:kick")]
    public function kickUser(Request $request): NyuwaResponse
    {
        return $this->service->kickUser((string) $request->input('id')) ?
            $this->success() : $this->error();
    }
}