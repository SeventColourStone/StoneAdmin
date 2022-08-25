<?php


use Phinx\Seed\AbstractSeed;

class SystemLoginLogInit extends AbstractSeed
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
      'id' =>        '16653573770912' ,
      'username' =>       'superAdmin',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'os' =>       'Windows 10',
      'browser' =>       'Chrome',
      'status' =>       '0',
      'message' =>       '登录成功',
      'login_time' =>        '2022-05-23 11:39:17' ,
      'remark' =>       '',
          ],
       [
      'id' =>        '16660099604640' ,
      'username' =>       'superAdmin',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'os' =>       'Windows 10',
      'browser' =>       'Chrome',
      'status' =>       '0',
      'message' =>       '登录成功',
      'login_time' =>        '2022-05-23 15:11:43' ,
      'remark' =>       '',
          ],
       [
      'id' =>        '16669767002272' ,
      'username' =>       'superAdmin',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'os' =>       'Windows 10',
      'browser' =>       'Chrome',
      'status' =>       '0',
      'message' =>       '登录成功',
      'login_time' =>        '2022-05-23 20:26:24' ,
      'remark' =>       '',
          ],

        ];

        $system_login_log = $this->table('system_login_log');
        $system_login_log->insert($data)
            ->save();

        // empty the table
        // $system_login_log->truncate();
    }
}
