<?php


use Phinx\Seed\AbstractSeed;

class SettingCrontabInit extends AbstractSeed
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
      'id' =>        '3890924964000' ,
      'name' =>       'urlCrontab',
      'type' =>       '3',
      'target' =>       'http://127.0.0.1:8787/',
      'parameter' =>       '',
      'rule' =>       '10 */1 * * * *',
      'running_times' =>        null,
      'last_running_time' =>        null,
      'singleton' =>       '1',
      'status' =>       '0',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2021-08-07 23:28:28' ,
      'updated_at' =>        '2022-05-20 11:07:55' ,
      'remark' =>       '请求127.0.0.1',
          ],
       [
      'id' =>        '10794906676384' ,
      'name' =>       '每月1号清理所有日志',
      'type' =>       '2',
      'target' =>       'AppSystemCrontabClearLogCrontab',
      'parameter' =>       '',
      'rule' =>       '0 0 0 1 * *',
      'running_times' =>        null,
      'last_running_time' =>        null,
      'singleton' =>       '1',
      'status' =>       '1',
      'created_by' =>        null,
      'updated_by' =>        null,
      'created_at' =>        '2022-04-11 11:15:48' ,
      'updated_at' =>        '2022-04-11 11:15:48' ,
      'remark' =>       '',
          ],

        ];

        $setting_crontab = $this->table('setting_crontab');
        $setting_crontab->insert($data)
            ->save();

        // empty the table
        // $setting_crontab->truncate();
    }
}
