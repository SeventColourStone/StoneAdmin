<?php


use Phinx\Seed\AbstractSeed;

class SystemDeptInit extends AbstractSeed
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
      'id' =>        '16384726752416' ,
      'parent_id' =>        '0' ,
      'level' =>       '0',
      'name' =>       '熙微科技',
      'leader' =>       '小吴',
      'phone' =>       '16888888888',
      'status' =>       '0',
      'sort' =>        '0' ,
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-17 09:47:45' ,
      'updated_at' =>        '2022-05-27 17:53:31' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '370870281687793664' ,
      'parent_id' =>        '16384726752416' ,
      'level' =>       '0,16384726752416',
      'name' =>       '运营部',
      'leader' =>       '小吴',
      'phone' =>       '13800138000',
      'status' =>       '0',
      'sort' =>        '0' ,
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-27 17:54:13' ,
      'updated_at' =>        '2022-05-27 17:54:13' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],

        ];

        $system_dept = $this->table('system_dept');
        $system_dept->insert($data)
            ->save();

        // empty the table
        // $system_dept->truncate();
    }
}
