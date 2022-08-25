<?php


use Phinx\Seed\AbstractSeed;

class SystemRoleInit extends AbstractSeed
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
      'id' =>        '16128263327905' ,
      'name' =>       '超级管理员（创始人）',
      'code' =>       'superAdmin',
      'data_scope' =>       '0',
      'status' =>       '0',
      'sort' =>        '0' ,
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '0' ,
      'created_at' =>        '2022-05-17 09:47:45' ,
      'updated_at' =>        '2022-05-17 09:47:45' ,
      'deleted_at' =>        null,
      'remark' =>       '系统内置角色，不可删除',
          ],
       [
      'id' =>        '369826443485511680' ,
      'name' =>       '22',
      'code' =>       '4124',
      'data_scope' =>       '0',
      'status' =>       '0',
      'sort' =>        '0' ,
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-24 20:46:23' ,
      'updated_at' =>        '2022-05-27 17:55:01' ,
      'deleted_at' =>        '2022-05-27 17:55:01' ,
      'remark' =>       '',
          ],
       [
      'id' =>        '370870465071153152' ,
      'name' =>       '运营',
      'code' =>       'yunying',
      'data_scope' =>       '1',
      'status' =>       '0',
      'sort' =>        '0' ,
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-27 17:54:57' ,
      'updated_at' =>        '2022-05-31 22:10:32' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '370870544326721536' ,
      'name' =>       '商家',
      'code' =>       'shangjia',
      'data_scope' =>       '0',
      'status' =>       '0',
      'sort' =>        '0' ,
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-27 17:55:16' ,
      'updated_at' =>        '2022-05-27 17:55:16' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],

        ];

        $system_role = $this->table('system_role');
        $system_role->insert($data)
            ->save();

        // empty the table
        // $system_role->truncate();
    }
}
