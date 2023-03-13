<?php


use Phinx\Seed\AbstractSeed;

class SettingConfigInit extends AbstractSeed
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
      'key' =>       'site_copyright',
      'value' =>       '',
      'name' =>       '版权信息',
      'group_name' =>       'system',
      'sort' =>        '96' ,
      'remark' =>       '',
          ],
       [
      'key' =>       'site_desc',
      'value' =>       '',
      'name' =>       '网站描述',
      'group_name' =>       'system',
      'sort' =>        '97' ,
      'remark' =>       '',
          ],
       [
      'key' =>       'site_keywords',
      'value' =>       '',
      'name' =>       '网站关键字',
      'group_name' =>       'system',
      'sort' =>        '98' ,
      'remark' =>       '',
          ],
       [
      'key' =>       'site_name',
      'value' =>       '',
      'name' =>       '网站名称',
      'group_name' =>       'system',
      'sort' =>        '99' ,
      'remark' =>       '',
          ],
       [
      'key' =>       'site_record_number',
      'value' =>       '',
      'name' =>       '网站备案号',
      'group_name' =>       'system',
      'sort' =>        '95' ,
      'remark' =>       '',
          ],
       [
      'key' =>       'site_storage_mode',
      'value' =>       'local',
      'name' =>       '上传存储模式',
      'group_name' =>       'system',
      'sort' =>        '93' ,
      'remark' =>       '',
          ],
       [
      'key' =>       'web_close',
      'value' =>       '1',
      'name' =>       '网站是否关闭',
      'group_name' =>       'extend',
      'sort' =>        '0' ,
      'remark' =>       '关闭网站后将无法访问',
          ],
       [
      'key' =>       'web_login_verify',
      'value' =>       '0',
      'name' =>       '后台登录验证码方式',
      'group_name' =>       'extend',
      'sort' =>        '0' ,
      'remark' =>       '0 前端验证，1 后端验证',
          ],

        ];

        $setting_config = $this->table('setting_config');
        $setting_config->insert($data)
            ->save();

        // empty the table
        // $setting_config->truncate();
    }
}
