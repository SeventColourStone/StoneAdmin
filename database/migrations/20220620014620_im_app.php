<?php

use Phinx\Db\Adapter\MysqlAdapter;

class ImApp extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('im_friend', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_0900_ai_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'identity' => 'enable',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '用户id',
                'after' => 'id',
            ])
            ->addColumn('friend_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '用户id',
                'after' => 'user_id',
            ])
            ->addColumn('state', 'char', [
                'null' => true,
                'limit' => 1,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'comment' => '审核状态',
                'after' => 'friend_id',
            ])
            ->addColumn('message', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'comment' => '信息',
                'after' => 'state',
            ])
            ->addColumn('create_time', 'datetime', [
                'null' => true,
                'after' => 'message',
            ])
            ->addColumn('update_time', 'datetime', [
                'null' => true,
                'after' => 'create_time',
            ])
            ->addColumn('create_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'after' => 'update_time',
            ])
            ->addColumn('update_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'after' => 'create_by',
            ])
            ->addColumn('del_flag', 'char', [
                'null' => false,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'after' => 'update_by',
            ])
            ->addColumn('remarks', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'after' => 'del_flag',
            ])
            ->addIndex(['user_id', 'friend_id'], [
                'name' => 'index2',
                'unique' => true,
            ])
            ->create();
        $this->table('im_group', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_0900_ai_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'comment' => '群名称',
                'after' => 'id',
            ])
            ->addColumn('avatar', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'comment' => '群头像',
                'after' => 'name',
            ])
            ->addColumn('master', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '群主',
                'after' => 'avatar',
            ])
            ->addColumn('announcement', 'string', [
                'null' => true,
                'limit' => 1000,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'comment' => '公告',
                'after' => 'master',
            ])
            ->addColumn('need_check', 'char', [
                'null' => true,
                'limit' => 1,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'comment' => '需要审核吗？',
                'after' => 'announcement',
            ])
            ->addColumn('create_time', 'datetime', [
                'null' => true,
                'after' => 'need_check',
            ])
            ->addColumn('update_time', 'datetime', [
                'null' => true,
                'after' => 'create_time',
            ])
            ->addColumn('create_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'after' => 'update_time',
            ])
            ->addColumn('update_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'after' => 'create_by',
            ])
            ->addColumn('del_flag', 'char', [
                'null' => false,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'after' => 'update_by',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'after' => 'del_flag',
            ])
            ->create();
        $this->table('im_group_user', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_0900_ai_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'identity' => 'enable',
            ])
            ->addColumn('group_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '群id ',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '用户id',
                'after' => 'group_id',
            ])
            ->addColumn('state', 'char', [
                'null' => true,
                'limit' => 1,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'comment' => '审核状态',
                'after' => 'user_id',
            ])
            ->addColumn('message', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'comment' => '申请信息',
                'after' => 'state',
            ])
            ->addColumn('create_time', 'datetime', [
                'null' => true,
                'after' => 'message',
            ])
            ->addColumn('update_time', 'datetime', [
                'null' => true,
                'after' => 'create_time',
            ])
            ->addColumn('create_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'after' => 'update_time',
            ])
            ->addColumn('update_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'after' => 'create_by',
            ])
            ->addColumn('del_flag', 'char', [
                'null' => false,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'after' => 'update_by',
            ])
            ->addColumn('remarks', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_0900_ai_ci',
                'encoding' => 'utf8mb4',
                'after' => 'del_flag',
            ])
            ->create();
        $this->table('im_message', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_0900_ai_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'identity' => 'enable',
            ])
            ->addColumn('to_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '接收人',
                'after' => 'id',
            ])
            ->addColumn('from_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '发送人',
                'after' => 'to_id',
            ])
            ->addColumn('send_time', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '发送时间',
                'after' => 'from_id',
            ])
            ->addColumn('content', 'string', [
                'null' => true,
                'limit' => 4000,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'comment' => '内容',
                'after' => 'send_time',
            ])
            ->addColumn('type', 'char', [
                'null' => true,
                'limit' => 1,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'comment' => '类型',
                'after' => 'content',
            ])
            ->addColumn('read_status', 'char', [
                'null' => true,
                'limit' => 1,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'comment' => '读状态',
                'after' => 'type',
            ])
            ->create();
    }
}
