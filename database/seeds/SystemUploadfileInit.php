<?php


use Phinx\Seed\AbstractSeed;

class SystemUploadfileInit extends AbstractSeed
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
      'id' =>        '367249904696819712' ,
      'storage_mode' =>       '1',
      'origin_name' =>       'image2021-3-3_19-18-44.png',
      'object_name' =>       '367249903102984192.png',
      'mime_type' =>       'image/png',
      'storage_path' =>       '20220517',
      'suffix' =>       'tmp',
      'size_byte' =>        '73511' ,
      'size_info' =>       '71.79KB',
      'url' =>       '/uploadfile/20220517/367249903102984192.png',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-17 18:08:08' ,
      'updated_at' =>        '2022-05-17 18:08:08' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],

        ];

        $system_uploadfile = $this->table('system_uploadfile');
        $system_uploadfile->insert($data)
            ->save();

        // empty the table
        // $system_uploadfile->truncate();
    }
}
