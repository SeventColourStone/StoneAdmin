<?php


use Phinx\Seed\AbstractSeed;

class SettingGenerateTablesInit extends AbstractSeed
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
      'id' =>        '375520143763046400' ,
      'table_name' =>       'finance_recharge_info',
      'table_comment' =>       '充值信息',
      'module_name' =>       '',
      'namespace' =>       '',
      'menu_name' =>       '',
      'belong_menu_id' =>        null,
      'package_name' =>       '',
      'type' =>       'single',
      'generate_type' =>       '0',
      'generate_menus' =>       '',
      'build_menu' =>       '0',
      'component_type' =>       '0',
      'options' =>       '',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-06-09 13:51:07' ,
      'updated_at' =>        '2022-06-09 13:51:07' ,
      'remark' =>       '',
          ],
       [
      'id' =>        '375520167347617792' ,
      'table_name' =>       'finance_score',
      'table_comment' =>       '用户积分信息',
      'module_name' =>       '',
      'namespace' =>       '',
      'menu_name' =>       '',
      'belong_menu_id' =>        null,
      'package_name' =>       '',
      'type' =>       'single',
      'generate_type' =>       '0',
      'generate_menus' =>       '',
      'build_menu' =>       '0',
      'component_type' =>       '0',
      'options' =>       '',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-06-09 13:51:12' ,
      'updated_at' =>        '2022-06-09 13:51:12' ,
      'remark' =>       '',
          ],
       [
      'id' =>        '375520185014026240' ,
      'table_name' =>       'finance_score_change_info',
      'table_comment' =>       '积分变动信息',
      'module_name' =>       '',
      'namespace' =>       '',
      'menu_name' =>       '',
      'belong_menu_id' =>        null,
      'package_name' =>       '',
      'type' =>       'single',
      'generate_type' =>       '0',
      'generate_menus' =>       '',
      'build_menu' =>       '0',
      'component_type' =>       '0',
      'options' =>       '',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-06-09 13:51:16' ,
      'updated_at' =>        '2022-06-09 13:51:16' ,
      'remark' =>       '',
          ],
       [
      'id' =>        '375521823736987648' ,
      'table_name' =>       'platform_biz_info',
      'table_comment' =>       '平台业务信息',
      'module_name' =>       'bizMonitor',
      'namespace' =>       'app\bizMonitor',
      'menu_name' =>       '业务信息',
      'belong_menu_id' =>        '375521539392536576' ,
      'package_name' =>       '',
      'type' =>       'single',
      'generate_type' =>       '1',
      'generate_menus' =>       'read,delete,recycle,changeStatus,numberOperation,export',
      'build_menu' =>       '0',
      'component_type' =>       '0',
      'options' =>       '{"relations":[],"tree_id":"","tree_parent_id":"","tree_name":""}',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-06-09 13:57:47' ,
      'updated_at' =>        '2022-06-09 14:01:04' ,
      'remark' =>       '',
          ],

        ];

        $setting_generate_tables = $this->table('setting_generate_tables');
        $setting_generate_tables->insert($data)
            ->save();

        // empty the table
        // $setting_generate_tables->truncate();
    }
}
