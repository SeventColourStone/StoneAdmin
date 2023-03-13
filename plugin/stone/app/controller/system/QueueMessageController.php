<?php


namespace plugin\stone\app\controller\system;


use plugin\stone\app\service\system\SystemQueueMessageService;
use plugin\stone\app\validate\system\SystemQueueMessageValidate;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class QueueMessageController extends NyuwaController
{

    #[Inject]
    protected SystemQueueMessageService $service;


    /**
     * @var SystemQueueMessageValidate 验证器
     */
    #[Inject]
    protected SystemQueueMessageValidate $validate;



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