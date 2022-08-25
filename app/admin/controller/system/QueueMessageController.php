<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemQueueMessageService;
use app\admin\validate\system\SystemQueueMessageValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class QueueMessageController extends NyuwaController
{

    /**
     * SystemQueueMessageService 服务
     * @Inject
     * @var SystemQueueMessageService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemQueueMessageValidate 验证器
     */
    protected $validate;



    /**
     * 接收消息列表
     */
    #[GetMapping("receiveList")]
    public function receiveList(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getReceiveMessage($request->all()));
    }

    /**
     * 已发送消息列表
     */
    #[GetMapping("sendList")]
    public function sendList(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getSendMessage($request->all()));
    }

    /**
     * 发私信
     */
    #[PostMapping("sendPrivateMessage")]
    public function sendPrivateMessage(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("send_private_message")->check($request->all());
        return $this->service->sendPrivateMessage($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 获取接收人列表
     */
    #[GetMapping("getReceiveUser")]
    public function getReceiveUser(Request $request): NyuwaResponse
    {
        return $this->success(
            $this->service->getReceiveUserList(
                (int) $request->input('id', 0),
                $request->all()
            )
        );
    }

    /**
     * 单个或批量删除数据到回收站
     */
    #[DeleteMapping("deletes/{ids}")]
    public function deletes(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 更新状态到已读
     */
    #[PutMapping("updateReadStatus/{ids}")]
    public function updateReadStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->updateDataStatus($data["id"]) ? $this->success() : $this->error();
    }


}