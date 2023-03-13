<?php


use Phinx\Seed\AbstractSeed;

class SystemUserInit extends AbstractSeed
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
      'id' =>        '1' ,
      'username' =>       '系统',
      'password' =>       '222',
      'user_type' =>       '100',
      'nickname' =>       '系统',
      'phone' =>       '',
      'email' =>       '',
      'avatar' =>       '',
      'signed' =>       '',
      'dashboard' =>       '',
      'dept_id' =>        null,
      'status' =>       '0',
      'login_ip' =>       '',
      'login_time' =>        null,
      'backend_setting' =>       '',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        null,
      'updated_at' =>        null,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '16128263327904' ,
      'username' =>       'superAdmin',
      'password' =>       '$2y$10$XwcOchqTEh09esZN9uKxQekzbeaYP6LKy7hBrLdeQR/M7G/ZpmAu6',
      'user_type' =>       '100',
      'nickname' =>       '宋子宁',
      'phone' =>       '16858888988',
      'email' =>       'admin@adminmine.com',
      'avatar' =>       '',
      'signed' =>       '',
      'dashboard' =>       'statistics',
      'dept_id' =>        '16384726752416' ,
      'status' =>       '0',
      'login_ip' =>       '127.0.0.1',
      'login_time' =>        '2022-05-23 20:26:24' ,
      'backend_setting' =>       '{"lang":"zh_CN","theme":"default","colorPrimary":"#BACFB5","layout":"default","layoutTags":true}',
      'created_by' =>        '0' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-17 09:47:45' ,
      'updated_at' =>        '2022-06-14 13:36:03' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '370872318156603392' ,
      'username' =>       'testyy1',
      'password' =>       '$2y$10$yfybfPMIYMalKP58sxH1DunX1WRRSViG8uvpmcLddy.kTDhVFngYm',
      'user_type' =>       '100',
      'nickname' =>       '测试运营小妹',
      'phone' =>       '',
      'email' =>       '',
      'avatar' =>       '',
      'signed' =>       '',
      'dashboard' =>       '',
      'dept_id' =>        '370870281687793664' ,
      'status' =>       '0',
      'login_ip' =>       '',
      'login_time' =>        null,
      'backend_setting' =>       '',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-27 18:02:19' ,
      'updated_at' =>        '2022-05-31 14:17:23' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],

        ];

        $system_user = $this->table('system_user');
        $system_user->insert($data)
            ->save();

        // empty the table
        // $system_user->truncate();
    }
}
