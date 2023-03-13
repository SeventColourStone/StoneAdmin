<?php

declare(strict_types=1);

namespace plugin\stone\app\service\system;


use plugin\stone\app\mapper\system\SystemQueueMessageMapper;
use plugin\stone\app\model\system\SystemQueueMessage;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;
use plugin\stone\nyuwa\vo\QueueMessageVo;

class SystemQueueMessageService extends AbstractService
{
    /**
     * @var SystemQueueMessageMapper
     */
    public $mapper;

    public function __construct(SystemQueueMessageMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * 获取用户未读消息
     * @param int $id
     * @return mixed
     */
    public function getUnreadMessage(int $id)
    {
        $params = [
            'user_id' => $id,
            'orderBy' => 'created_at',
            'orderType' => 'desc',
            'getReceive' => true,
            'read_status' => 0,
        ];
        return $this->mapper->getPageList($params, false);
    }

    /**
     * 获取收信箱列表数据
     * @param array $params
     * @return array
     */
    public function getReceiveMessage(array $params = []): array
    {
        $params['getReceive'] = true;
        unset($params['getSend']);
        return $this->mapper->getPageList($params, false);
    }

    /**
     * 获取已发送列表数据
     * @param array $params
     * @return array
     */
    public function getSendMessage(array $params = []): array
    {
        $params['getSend'] = true;
        unset($params['getReceive']);
        return $this->mapper->getPageList($params, false);
    }

    /**
     * 发私信
     * @param array $data
     * @return bool
     * @throws \Throwable
     */
    public function sendPrivateMessage(array $data): bool
    {
        $queueMessage = new QueueMessageVo();
        $queueMessage->setTitle($data['title']);
        $queueMessage->setContent($data['content']);
        // 固定私信类型
        $queueMessage->setContentType(SystemQueueMessage::TYPE_PRIVATE_MESSAGE);
        $queueMessage->setSendBy(nyuwa_user()->getId());
        return push_queue_message($queueMessage, $data['users']) !== -1;
    }

    /**
     * 获取接收人列表
     * @param int $id
     * @param array $params
     * @return array
     */
    public function getReceiveUserList(int $id, array $params = []): array
    {
        return $this->mapper->getReceiveUserList($id, $params);
    }

    /**
     * 更新中间表数据状态
     * @param String $ids
     * @param string $columnName
     * @param string $value
     * @return bool
     */
    public function updateDataStatus(String $ids, string $columnName = 'read_status', string $value = '1'): bool
    {
        return $this->mapper->updateDataStatus(explode(',', $ids), $columnName, $value);
    }

}
