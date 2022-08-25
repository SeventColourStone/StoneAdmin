<?php


use Phinx\Seed\AbstractSeed;

class SystemPostInit extends AbstractSeed
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
      'id' =>        '370035712260571136' ,
      'name' =>       '客服',
      'code' =>       'kefu',
      'sort' =>        '0' ,
      'status' =>       '0',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-25 10:37:56' ,
      'updated_at' =>        '2022-05-25 10:37:56' ,
      'deleted_at' =>        null,
      'remark' =>       '客服',
          ],
       [
      'id' =>        '370035772125872128' ,
      'name' =>       '项目',
      'code' =>       'xiangmu',
      'sort' =>        '0' ,
      'status' =>       '0',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-25 10:38:11' ,
      'updated_at' =>        '2022-05-25 10:38:11' ,
      'deleted_at' =>        null,
      'remark' =>       '项目',
          ],
       [
      'id' =>        '370035832435769344' ,
      'name' =>       '运营',
      'code' =>       'yunying',
      'sort' =>        '0' ,
      'status' =>       '0',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-25 10:38:25' ,
      'updated_at' =>        '2022-05-25 10:38:25' ,
      'deleted_at' =>        null,
      'remark' =>       '运营',
          ],

        ];

        $system_post = $this->table('system_post');
        $system_post->insert($data)
            ->save();

        // empty the table
        // $system_post->truncate();
    }
}
