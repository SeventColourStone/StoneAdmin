<?php


use Phinx\Seed\AbstractSeed;

class SystemDictTypeInit extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
                   [
      'id' =>        '2035075124896' ,
      'name' =>       '数据表引擎',
      'code' =>       'table_engine',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-06-27 00:36:42' ,
      'updated_at' =>        '2021-06-27 13:33:29' ,
      'deleted_at' =>        null,
      'remark' =>       '数据表引擎字典',
          ],
       [
      'id' =>        '2058928973472' ,
      'name' =>       '存储模式',
      'code' =>       'upload_mode',
      'status' =>       '0',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2021-06-27 13:33:11' ,
      'updated_at' =>        '2022-05-26 16:09:38' ,
      'deleted_at' =>        null,
      'remark' =>       '上传文件存储模式',
          ],
       [
      'id' =>        '2059023428768' ,
      'name' =>       '数据状态',
      'code' =>       'data_status',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-06-27 13:36:16' ,
      'updated_at' =>        '2021-06-27 13:36:34' ,
      'deleted_at' =>        null,
      'remark' =>       '通用数据状态',
          ],
       [
      'id' =>        '3959885616288' ,
      'name' =>       '后台首页',
      'code' =>       'dashboard',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-08-09 12:53:17' ,
      'updated_at' =>        '2021-08-09 12:53:17' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '3959928216736' ,
      'name' =>       '性别',
      'code' =>       'sex',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-08-09 12:54:40' ,
      'updated_at' =>        '2021-08-09 12:54:40' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '8126617434272' ,
      'name' =>       '后台公告类型',
      'code' =>       'backend_notice_type',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-11-11 17:29:05' ,
      'updated_at' =>        '2021-11-11 17:29:14' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '8259136986784' ,
      'name' =>       '请求方式',
      'code' =>       'request_mode',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-11-14 17:22:52' ,
      'updated_at' =>        '2021-11-14 17:22:52' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '8619580222112' ,
      'name' =>       '接口数据类型',
      'code' =>       'api_data_type',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-11-22 20:56:03' ,
      'updated_at' =>        '2021-11-22 20:56:03' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '10074681620640' ,
      'name' =>       '队列生产状态',
      'code' =>       'queue_produce_status',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-12-25 18:22:38' ,
      'updated_at' =>        '2021-12-25 18:22:38' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '10074702532768' ,
      'name' =>       '队列消费状态',
      'code' =>       'queue_consume_status',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-12-25 18:23:19' ,
      'updated_at' =>        '2021-12-25 18:23:19' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '10074866778272' ,
      'name' =>       '队列消息类型',
      'code' =>       'queue_msg_type',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2021-12-25 18:28:40' ,
      'updated_at' =>        '2021-12-25 18:28:40' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '10074866789575' ,
      'name' =>       '附件类型',
      'code' =>       'attachment_type',
      'status' =>       '0',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2022-03-17 14:49:23' ,
      'updated_at' =>        '2022-03-17 14:49:23' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],

        ];

        $system_dict_type = $this->table('system_dict_type');
        $system_dict_type->insert($data)
            ->save();

        // empty the table
        // $system_dict_type->truncate();
    }
}
