<?php

namespace app\command;

use app\admin\model\setting\SettingGenerateTables;
use app\admin\service\setting\SettingGenerateColumnsService;
use app\admin\service\setting\SettingGenerateTablesService;
use app\admin\service\system\SystemMenuService;
use app\admin\service\system\SystemUserService;
use DI\Annotation\Inject;
use nyuwa\generator\ApiGenerator;
use nyuwa\generator\ControllerGenerator;
use nyuwa\generator\DtoGenerator;
use nyuwa\generator\MapperGenerator;
use nyuwa\generator\ModelGenerate;
use nyuwa\generator\ServiceGenerator;
use nyuwa\generator\SqlGenerator;
use nyuwa\generator\ValidateGenerator;
use nyuwa\generator\VueIndexGenerator;
use nyuwa\generator\VueSaveGenerator;
use support\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class QuickGenInfoCommand extends Command
{
    protected static $defaultName = 'quick:gen:info';
    protected static $defaultDescription = 'QuickGenInfo';

    private $systemTable = [
        "phinx_migrations",
        "setting_config",
        "setting_crontab",
        "setting_crontab_log",
        "setting_generate_columns",
        "setting_generate_tables",
        "system_dept",
        "system_dict_data",
        "system_dict_type",
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
    private $systemUserService;


    /**
     * 菜单服务
     * @var SystemMenuService
     */
    private $systemMenuService;

    /**
     * 信息表服务
     * @var SettingGenerateTablesService
     */
    private $settingGenerateTablesService;


    /**
     * 信息字段表服务
     * @Inject
     * @var SettingGenerateColumnsService
     */
    protected $settingGenerateColumnsService;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('module_name', InputArgument::OPTIONAL, 'module_name description');
        $this->addArgument('menu_name', InputArgument::OPTIONAL, 'menu_name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $module_name = $input->getArgument('module_name');
        $menu_name = $input->getArgument('menu_name');
        $output->writeln('Hello QuickGenTable');
        $this->settingGenerateTablesService = nyuwa_app(SettingGenerateTablesService::class);
        $this->settingGenerateColumnsService = nyuwa_app(SettingGenerateColumnsService::class);
        $this->systemUserService = nyuwa_app(SystemUserService::class);
        $this->systemMenuService = nyuwa_app(SystemMenuService::class);

        $tables = Db::select("SELECT * FROM information_schema.TABLES where TABLE_TYPE = :key ;",['key'=>"BASE TABLE"]);

//        $menuInfo = [
//                "name"=> "社区",
//                "path"=> "",
//                "component"=> "",
//                "code"=> "bbs",
//                "status"=> "0",
//                "is_hidden"=> "1",
//                "icon"=> "",
//                "sort"=> 0,
//                "type"=> "M",
//                "restful"=> "1",
//                "top"=> 1,
//                "parent_id"=> 0,
//                "route"=> "bbs"
//        ];
//        $this->systemMenuService->save($menuInfo);

        $tableGenInfo = [];

        foreach ($tables as $tableInfo){
            $tableName =  $tableInfo->TABLE_NAME;
            $tableComment =  $tableInfo->TABLE_COMMENT;
            if (in_array($tableName,$this->systemTable)){
                continue;
            }

            $tableGenInfo []= [
                "name"=> $tableName ,
                "comment"=>$tableComment,
                'menu_name' => empty($tableComment)?$tableName:$tableComment,
                'module_name' => "bbs",
                'namespace' => "app\bbs",
                'belong_menu_id' => "382432005524029440",
                'generate_menus' => "save,update,read,delete,recycle,changeStatus,numberOperation,import,export",
//                "options" => [
//                    ["relations"=>[["name"=>"userInfo","type"=>"hasOne","model"=>"\\app\\admin\\model\\system\\SystemUser","foreignKey"=>"id","localKey"=>"user_id","table"=>""]],"tree_id"=>"user_id","tree_parent_id"=>"friend_id","tree_name"=>"message"]
//                ]
            ];


        }
//        var_dump($tables);

        //1、加载进代码生成器


        $loadTableIds = $this->settingGenerateTablesService->loadTable($tableGenInfo);
        var_dump($loadTableIds);
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
                if (get_class($class) == 'nyuwa\generator\SqlGenerator'){
                    $class->setGenInfo($model, $adminId)->generator($genFilePath);
                } else {
                    $class->setGenInfo($model)->generator($genFilePath);
                }
            }
        }



        //options,添加关联关系以及树表类型。
        //{"relations":[{"name":"userInfo","type":"hasOne","model":"\\app\\admin\\model\\system\\SystemUser","foreignKey":"id","localKey":"user_id","table":""}],"tree_id":"user_id","tree_parent_id":"friend_id","tree_name":"message"}

        //{
        //    "name": "im",
        //    "label": "聊天",
        //    "version": "1.0.0",
        //    "description": "聊天"
        //}
        return self::SUCCESS;
    }

}
