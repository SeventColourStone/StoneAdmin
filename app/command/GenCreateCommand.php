<?php


namespace app\command;

use app\admin\service\generate\GenerateColumnsService;
use app\admin\service\generate\GenerateTablesService;
use DI\Annotation\Inject;
use nyuwa\exception\NyuwaException;
use nyuwa\traits\GencodeTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 根据现有的表创建curd生成器数据
 * Class GenCreateCommand
 * @package app\command
 */
class GenCreateCommand extends Command
{
    use GencodeTrait;
    protected static $defaultName = 'stone:gen';
    protected static $defaultDescription = '一键检索项目数据库，并根据现有的表创建curd生成器数据';

    /**
     * @var GenerateColumnsService
     * @Inject
     */
    protected $generateColumnsService;

    /**
     * @var GenerateTablesService
     * @Inject
     */
    protected $generateTablesService;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addOption('table', "table",InputOption::VALUE_OPTIONAL, 'table name');
        $this->addOption('all', "all",InputOption::VALUE_OPTIONAL, 1);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = $input->getOption('table');
        if (!$table) {
            $tables =$this->getAllTable();

            foreach ($tables as $tableInfo){
                $table = $tableInfo->TABLE_NAME;
                if ($table == "migrations"){
                    continue;
                }
                $this->createGenInfo($table);
            }
        }else{
            if ($table == "migrations"){
                throw new NyuwaException("不能执行该表:".$table);
            }
            $this->createGenInfo($table);
        }



        return 1;

    }

    public function createGenInfo($table){
        $tableCamelCase = nyuwa_camelize($table);
        $tableSmallCamelCase = nyuwa_camelize($table,'_',0);
        $tableUnderScoreCase = nyuwa_uncamelize($table);
        $tableCenterScoreCase = nyuwa_uncamelize($tableCamelCase,"-");
        $this->generateColumnsService = nyuwa_app(GenerateColumnsService::class);

        $this->generateTablesService = nyuwa_app(GenerateTablesService::class);

        $tableBaseInfo = $this->getTableBaseInfo($table);
        $tableDetailInfo = $this->getTableDetailInfo($table);
//        var_dump(json_encode($tableBaseInfo));


        var_dump("---------------------------------");


//        var_dump(json_encode($tableDetailInfo));

        //插入数据

        $tableInfo = [
            "table_name" => $tableBaseInfo->TABLE_NAME,  //表名称
            "table_comment" => $tableBaseInfo->TABLE_COMMENT,  //表注释
//            "module_name" => "'required|max:100",  //所属模块
//            "namespace" => "'required|max:255",  //命名空间
//            "menu_name" => "'required|max:100",  //生成菜单名
//            "belong_menu_id" => "required",  //所属菜单
//            "package_name" => "'required|max:100",  //控制器包名
//            "type" => "'required|max:100",  //生成类型
//            "generate_type" => "required|string",  //生成方式
//            "options" => "'required|max:1500",  //其他业务选项
//            "files" => "'required|max:1500",  //生成文件路径
//            "created_by" => "required",  //创建者
//            "updated_by" => "required",  //更新者
//            "created_at" => "required|date",  //创建时间
//            "updated_at" => "required|date",  //更新时间
//            "deleted_at" => "required|date",  //删除时间
            "remark" => $tableBaseInfo->TABLE_COMMENT,  //备注
        ];

        $genTableId = $this->generateTablesService->save($tableInfo);

        $list = [];
        foreach ($tableDetailInfo as $item){
            $is_pk = 1;
            if ($item->COLUMN_KEY == "PRI"){
                $is_pk = 0;
            }
            $genColumns = [
                "table_id" => $genTableId,  //所属表ID
                "column_name" => $item->COLUMN_NAME,  //字段名称
                "column_comment" => $item->COLUMN_COMMENT,  //字段注释
                "data_type" => $item->DATA_TYPE,  //字段类型
                "date_max_len" => empty($item->CHARACTER_MAXIMUM_LENGTH)?0:$item->CHARACTER_MAXIMUM_LENGTH,  //字段长度
                "is_pk" => $is_pk,  //是否主键
                "is_required" => 1,  //是否必填
                "is_insert" => 1,  //是否表单插入
                "is_edit" => 1,  //是否编辑
                "is_list" => 1,  //是否列表显示
                "is_query" => 1,  //是否查询字段
//                "query_type" => "'required|max:100",  //查询方式
//                "view_type" => "'required|max:100",  //页面控件
//                "dict_type" => "'required|max:200",  //字典类型
                "sort" => $item->ORDINAL_POSITION,  //排序
                "remark" => $item->COLUMN_COMMENT,  //备注
            ];
            $list []= $genColumns;
        }
        $this->generateColumnsService->batchSave($list);
    }
}