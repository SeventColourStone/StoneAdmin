<?php


namespace app\admin\service\system;


use app\admin\mapper\system\SystemNoticeMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemNoticeService extends AbstractService
{
    /**
     * @Inject
     * @var SystemNoticeMapper
     */
    public $mapper;

    /**
     * 保存公告
     * @Transaction
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Throwable
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function save(array $data): string
    {
        $message = new QueueMessageVo();
        $message->setTitle($data['title']);
        $message->setContentType(
            $data['type'] === '1'
                ? SystemQueueMessage::TYPE_NOTICE
                : SystemQueueMessage::TYPE_ANNOUNCE
        );
        $message->setContent($data['content']);
        $message->setSendBy(user()->getId());
        $data['message_id'] = push_queue_message($message, $data['users']);

        if ($data['message_id'] !== -1) {
            return parent::save($data);
        }

        throw new NormalStatusException;
    }

}
