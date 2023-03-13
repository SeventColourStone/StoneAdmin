<?php

namespace app\command;

use DI\Attribute\Inject;
use plugin\stone\app\model\setting\SettingGenerateTables;
use plugin\stone\app\service\setting\SettingGenerateColumnsService;
use plugin\stone\app\service\setting\SettingGenerateTablesService;
use plugin\stone\app\service\system\SystemUserService;
use plugin\stone\nyuwa\generator\ApiGenerator;
use plugin\stone\nyuwa\generator\ControllerGenerator;
use plugin\stone\nyuwa\generator\DtoGenerator;
use plugin\stone\nyuwa\generator\MapperGenerator;
use plugin\stone\nyuwa\generator\ModelGenerate;
use plugin\stone\nyuwa\generator\ServiceGenerator;
use plugin\stone\nyuwa\generator\SqlGenerator;
use plugin\stone\nyuwa\generator\ValidateGenerator;
use plugin\stone\nyuwa\generator\VueIndexGenerator;
use plugin\stone\nyuwa\generator\VueSaveGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class QuickGenTableCommand extends Command
{
    protected static $defaultName = 'quick:gen:table';
    protected static $defaultDescription = 'QuickGenTable';

    private array $systemTable = [
        "phinx_migrations",
        "setting_config",
        "setting_crontab",
        "setting_crontab_log",
        "setting_generate_columns",
        "setting_generate_tables",
        "system_dept",
        "system_dict_data",
        "system_distribution",
        "system_login_log",
        "system_menu",
        "system_notice",
        "system_oper_log",
        "system_post",
        "system_queue_log",
        "system_queue_message",
        "system_queue_message_receive",
        "system_role",
        "system_role_dept",
        "system_role_menu",
        "system_uploadfile",
        "system_user",
        "system_user_post",
        "system_user_role",
    ];


    /**
     * 信息表服务
     * @var SystemUserService
     */
    private SystemUserService $systemUserService;

    /**
     * 信息表服务
     * @var SettingGenerateTablesService
     */
    private SettingGenerateTablesService $settingGenerateTablesService;


    /**
     * 信息字段表服务
     * @var SettingGenerateColumnsService
     */
    protected SettingGenerateColumnsService $settingGenerateColumnsService;

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Create curd')
            ->setHelp('Create curd')
            ->setDefinition([
                new InputOption('table','t' ,InputOption::VALUE_REQUIRED, '数据库表 名称'),
                new InputOption('belong_menu_id', 'bm', InputOption::VALUE_OPTIONAL, '生成的父级菜单id 不填则默认创建一个业务管理菜单，并归纳在这个菜单下面'),
                new InputOption('module_name', 'mn', InputOption::VALUE_OPTIONAL, '应用模块名 默认stone','stone'),

                new InputOption('menu_name', 'm', InputOption::VALUE_OPTIONAL, '生成的菜单名 默认取表描述'),
                new InputOption('generate_menus', 'gm', InputOption::VALUE_OPTIONAL, '生成的功能按钮','save,update,read,delete,recycle,changeStatus,numberOperation,import,export'),

            ]);
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //打印
        $output->writeln([
            'gen curd',
        ]);
        $table = $input->getOption('table');
        $belong_menu_id = $input->getOption('belong_menu_id');
        $menu_name = $input->getOption('menu_name');
        $module_name = $input->getOption('module_name');
        $generate_menus = $input->getOption('generate_menus');

        $output->writeln('table: ' . $table);
        $output->writeln('belong_menu_id: ' . $belong_menu_id );
        $output->writeln('module_name: ' . $module_name);
        $output->writeln('menu_name: ' . $menu_name);
        $output->write('generate_menus: '.$generate_menus);


        if (empty($menu_name)){
            $menu_name = $table."管理";
        }
        if (empty($belong_menu_id)){
            //不存在选择默认菜单
            $belong_menu_id = 5000;
        }

        $loadTableInfo = [
            "name"=>$table,
            "comment"=>"{$table} 表命令行生成",
            'menu_name' => $menu_name,
            'module_name' => $module_name,
            'namespace' => "plugin\\{$module_name}\\app",
            'belong_menu_id' => $belong_menu_id,
            'generate_menus' => $generate_menus,
        ];

        $this->gencode($loadTableInfo);

        return self::SUCCESS;
    }


    function gencode($loadTableInfo){
        $this->settingGenerateTablesService = nyuwa_app(SettingGenerateTablesService::class);
        $this->settingGenerateColumnsService = nyuwa_app(SettingGenerateColumnsService::class);
        $this->systemUserService = nyuwa_app(SystemUserService::class);

        //1、加载进代码生成器
        $loadTableIds = $this->settingGenerateTablesService->loadTable([
            $loadTableInfo
        ]);
        $adminId = 1;
        foreach ($loadTableIds as $id){
            /** @var SettingGenerateTables $model */
            $model = $this->settingGenerateTablesService->read($id);

            $classList = [
                ControllerGenerator::class,
                ModelGenerate::class,//ModelGenerator::class,
                ServiceGenerator::class,
                MapperGenerator::class,
                ValidateGenerator::class, //RequestGenerator::class,
                ApiGenerator::class,
                VueIndexGenerator::class,
                VueSaveGenerator::class,
                SqlGenerator::class,
                DtoGenerator::class,
            ];

            $genFilePath = env("GENCODE_PATH","D:/home");
            foreach ($classList as $cls) {
                $class = nyuwa_app($cls);
                if (get_class($class) == 'plugin\stone\nyuwa\generator\SqlGenerator'){
                    $class->setGenInfo($model, $adminId)->generator($genFilePath);
                } else {
                    $class->setGenInfo($model)->generator($genFilePath);
                }
            }
        }
    }


    //下划线命名到驼峰命名
    function toCamelCase($str, $toOne = false)
    {
        $array = explode('_', $str);
        $result = $toOne ? ucfirst($array[0]) : $array[0];
        $len = count($array);
        if ($len > 1) {
            for ($i = 1; $i < $len; $i++) {
                $result .= ucfirst($array[$i]);
            }
        }
        return $result;
    }


}


/**
 * 模板
$i = [
[
"name"=>"im_friend",
"comment"=>"im 好友",
'menu_name' => "好友管理hhh",
'module_name' => "im",
'namespace' => "app\im",
'belong_menu_id' => "3000",
'generate_menus' => "save,update,read,delete,recycle,changeStatus,numberOperation,import,export",
"options" => [
["relations"=>[["name"=>"userInfo","type"=>"hasOne","model"=>"\\app\\admin\\model\\system\\SystemUser","foreignKey"=>"id","localKey"=>"user_id","table"=>""]],"tree_id"=>"user_id","tree_parent_id"=>"friend_id","tree_name"=>"message"]
]
],
[
"name"=>"bbs_articles",
"comment"=>"bbs 文章",
'menu_name' => "文章管理hhh",
'module_name' => "bbs",
'namespace' => "app\bbs",
'belong_menu_id' => "3000",
'generate_menus' => "save,update,read,delete,recycle,changeStatus,numberOperation,import,export",
]
]
 */
