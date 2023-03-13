<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemQueueMessage;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;
use support\Db;

/**
 * 队列消息表
 * Class SystemQueueMessageMapper
 * @package plugin\stone\app\mapper\core
 */
class SystemQueueMessageMapper extends AbstractMapper
{
    /**
     * @var SystemQueueMessage
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemQueueMessage::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['title'])) {
            $query->where('title', 'like', '%'.$params['content_type'].'%');
        }

        // 内容类型
        if (isset($params['content_type'])) {
            $query->where('content_type', '=', $params['content_type']);
        }

        // 获取收信数据
        if (isset($params['getReceive'])) {
            $query->with(['sendUser' => function($query) {
                $query->select([ 'id', 'username', 'nickname' ]);
            }]);
            $prefix = env('DB_PREFIX');
            $readStatus = $params['read_status'] ?? 'all';
            $sql = <<<sql
id IN ( 
    SELECT `message_id` FROM `{$prefix}system_queue_message_receive` WHERE `user_id` = ?
    AND if (? <> 'all', `read_status` = ?, ' 1 = 1 ')
)
sql;
            $query->whereRaw($sql, [ $params['user_id'] ?? nyuwa_user()->getId(), $readStatus, $readStatus ]);
        }

        // 收取发信数据
        if (isset($params['getSend'])) {
            $query->where('send_by', nyuwa_user()->getId());
        }

        return $query;
    }

    /**
     * 获取接收人列表
     * @param int $id
     * @return array
     */
    public function getReceiveUserList(int $id): array
    {
        $prefix = env('DB_PREFIX');
        $paginate = Db::table('system_user as u')
            ->select(Db::raw("{$prefix}u.username, {$prefix}u.nickname, if ({$prefix}r.read_status = '1', '已读', '未读') as read_status "))
            ->join('system_queue_message_receive as r', 'u.id', '=', 'r.user_id')
            ->where('r.message_id', $id)
            ->paginate(
                $params['pageSize'] ?? $this->model::PAGE_SIZE,
                ['*'],
                'page',
                $params['page'] ?? 1
            );

        return $this->setPaginate($paginate);
    }

    /**
     * 保存数据
     * @Transaction
     * @param array $data
     * @return string
     */
    public function save(array $data): string
    {
        $receiveUsers = $data['receive_users'];
        $this->filterExecuteAttributes($data);
        $model = $this->model::create($data);
        $model->receiveUser()->sync($receiveUsers);
        return $model->{$model->getKeyName()};
    }

    /**
     * 删除消息
     * @param array $ids
     * @return bool
     * @throws \Exception
     * @Transaction
     */
    public function delete(array $ids): bool
    {
        foreach ($ids as $id) {
            $model = $this->model::find($id);
            if ($model) {
                $model->receiveUser()->detach();
                $model->delete();
            }
        }
        return true;
    }

    /**
     * 更新中间表数据状态
     * @param array $ids
     * @param string $columnName
     * @param string $value
     * @return bool
     */
    public function updateDataStatus(array $ids, string $columnName = 'read_status', string $value = '1'): bool
    {
        foreach ($ids as $id) {
            $result = Db::table('system_queue_message_receive')
                ->where('message_id', $id)
                ->where('user_id', nyuwa_user()->getId())
                ->value($columnName);

            if ($result != $value) {
                Db::table('system_queue_message_receive')
                    ->where('message_id', $id)
                    ->where('user_id', nyuwa_user()->getId())
                    ->update([ $columnName => $value ]);
            }
        }

        return true;
    }
}
