<?php


namespace app\command;


use app\admin\service\system\SystemDictDataService;
use app\admin\service\system\SystemDictFieldService;
use app\admin\service\system\SystemDictTypeService;
use app\admin\service\system\SystemMenuService;
use nyuwa\enum\GenCodeEnum;
use nyuwa\enum\GenFormEnum;
use nyuwa\exception\NyuwaException;
use nyuwa\exception\NyuwaGencodeException;
use nyuwa\traits\GencodeTrait;
use support\Db;
use support\Log;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 获取表字段基础信息，准备进行一系列的从后端、前端逻辑
 * 后端：控制器，验证器，路由，服务，mapper，model等一系列的生成，目前考虑单表。
 * 前端：列表页、新增页、更新页、
 *
 *
//单表
//根据表名生成对应的变量

//1、后端： 控制器名、验证器名、服务名、mapper名、模型名、

//2、ui端：表格id、toolbar_id、表单id、api 接口、cols字段、save表单绑定、表单类型生成
 */
class CurdCommand extends Command
{
    use GencodeTrait;
    protected static $defaultDescription = '一站式智能curd生成器';

    protected $stubList = [];

    /**
     * @var string 默认接口应用名
     */
    protected $defaultCurdAppName = "admin";
    /**
     * @var string 默认ui应用名
     */
    protected $defaultCurdUiAppName = "adminUi";

    /**
     * @var string 默认业务名
     */
    protected $defaultCurdModuleName = "core";//"generate";//"core";//"generate";//"core";//"business";


    /**
     * 表单操作排除字段
     */
    protected $ignoreFormFields = ["created_by","updated_by","created_at","updated_at","deleted_at", 'remark'];


    /**
     * 列表表格操作排除字段
     */
    protected $ignoreTableFields = ["id","password","created_by","updated_by","updated_at","deleted_at","remark"];

    /**
     * 初始的字典关联字段
     * @var array
     */
    protected $initDictFields = ["status"];


    /**
     * 初始的表关联字段
     * @var array
     */
    protected $initRelationFields = ["created_by","updated_by"];


    /**
     * 应用命名排除字段
     */
    protected $ignoreApp = ["app","adminUi"];//"admin",

    /**
     * @var string[] 模块排除字段
     */
    protected $ignoreModule = ['system'];

    /**
     * @var string[] 基础方法
     */
    protected $commonApi = [
        ["path"=>"index","name"=>"列表"],
        ["path"=>"recycle","name"=>"回收站"],
        ["path"=>"save","name"=>"保存"],
        ["path"=>"read","name"=>"读取"],
        ["path"=>"update","name"=>"更新"],
        ["path"=>"delete","name"=>"删除"],
        ["path"=>"realDelete","name"=>"强制删除"],
        ["path"=>"recovery","name"=>"恢复"],
        ["path"=>"import","name"=>"导入"],
        ["path"=>"export","name"=>"导出"],
    ];



    protected function configure()
    {
        $this
            ->setName('stone:curd')
            ->addOption('table', 't', InputOption::VALUE_REQUIRED, 'table name without prefix', null)
            ->addOption('module', 'mod', InputOption::VALUE_REQUIRED, 'module name without prefix',null)
            ->addOption('menu', 'menu', InputOption::VALUE_OPTIONAL, 'module name without prefix',null)
            ->addOption('backup', 'bp', InputOption::VALUE_OPTIONAL, 'force override or force delete,without tips', true)
            ->setDescription('Build CRUD controller and model from table');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $table = $input->getOption('table') ?: '';
        $output->writeln('CURD 表信息如下：');
        $module = $input->getOption('module') ?: '';
        $backup = $input->getOption('backup') ?: true;
        if (!$table) {
            throw new NyuwaException("table name can't empty");
        }

        if (!$module){
            $module =  $this->defaultCurdModuleName;
        }
        $tableCamelCase = nyuwa_camelize($table);
        $tableSmallCamelCase = nyuwa_camelize($table,'_',0);
        $tableUnderScoreCase = nyuwa_uncamelize($table);
        $tableCenterScoreCase = nyuwa_uncamelize($tableCamelCase,"-");


        $tableBaseInfo = $this->getTableBaseInfo($table);
        $tableDetailInfo = $this->getTableDetailInfo($table);
        if (empty($tableBaseInfo->TABLE_COMMENT)){
            throw new NyuwaException("生成的curd表需要添加注释");
        }
        //所有文件生成的基础参数
        $basicConfigInfo = [
            "tableComment"=>$tableBaseInfo->TABLE_COMMENT, //表注释
            "tableUnderScoreCase"=>$tableUnderScoreCase,  // 表名下划线格式
            "tableCenterScoreCase"=>$tableCenterScoreCase, //表名中划线式，用作前端id名
            "tableCamelCase"=>$tableCamelCase, //表名大驼峰式
            "tableSmallCamelCase"=>$tableSmallCamelCase, //表名小驼峰式
        ];

        [$uiUrlPath,$apiUrlPath] =$this->singleTableCreate($table,$module,$backup,$tableDetailInfo,$basicConfigInfo);
//        $parentMenuId = 347228948263862272;//工作空间
//        $parentMenuId = 347228950654615552;//系统中心
//        $parentMenuId = 347228951032102912;//数据中心
        $parentMenuId = 10001;//开发中心
//
        $this->generateCurdMenu($parentMenuId,$basicConfigInfo,$apiUrlPath,$uiUrlPath);

        return 1;
    }


}
