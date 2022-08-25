<?php


namespace nyuwa\traits;


use app\admin\model\system\SystemMenu;
use app\admin\service\system\SystemDictDataService;
use app\admin\service\system\SystemDictFieldService;
use app\admin\service\system\SystemDictTypeService;
use app\admin\service\system\SystemMenuService;
use MyCLabs\Enum\Enum;
use nyuwa\enum\GenCodeEnum;
use nyuwa\enum\GenFormEnum;
use nyuwa\exception\NyuwaException;
use nyuwa\exception\NyuwaGencodeException;
use support\Db;
use support\Log;

trait GencodeTrait
{

    /**
     * 获取各个文件的关键文件值
     * @param $app  应用名
     * @param $module  支持 biz.sss.sss  || biz/sss/cvd  || biz\sss\abc || \biz\sss\abc\
     * @param $table 表名
     * @param string $string
     */
    private function getParseCoreKey($app,$module, $table,GenCodeEnum $genCodeEnum)
    {
        //把模块格式过滤一遍
        $module = str_replace(['.', '/', '\\'], '/', $module);
        $arr = explode('/', $module);
        $arr = array_filter($arr);//去掉空值,得到可靠的模块名
        $modulePath = implode(DS,$arr);
//        var_dump($modulePath);
//        if ($app == "")
//            $app = "admin";

        $tableCamelCase = nyuwa_camelize($table);
        $tableSmallCamelCase = nyuwa_camelize($table,'_',0);
        $tableUnderScoreCase = nyuwa_uncamelize($table);
        $tableCenterScoreCase = nyuwa_uncamelize($tableCamelCase,"-");
        if ($genCodeEnum->is(GenCodeEnum::ApiController)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::ApiController);
            $apiControllerPath = $this->appApiControllerPath($app);
            $apiControllerPath = $apiControllerPath .DS. $modulePath;
            $apiControllerNamespace = $apiControllerPath;
            //获取接口url组路径
            $apiUrlPath = "/{$app}/api/{$module}/$tableSmallCamelCase";
            $apiUrlPath = str_replace(['/admin/'],"/",$apiUrlPath);
            $apiControllerFileName = $tableCamelCase."Controller.php";
            $parseData = [$apiControllerPath,$apiControllerNamespace,$apiControllerFileName,$apiUrlPath];
            var_dump($parseData);
            return $parseData;
        }
        if ($genCodeEnum->is(GenCodeEnum::ApiService)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::ApiService);
            $appServicePath = $this->appServicePath($app);
            $appServicePath = $appServicePath .DS. $modulePath;
            $appServiceNamespace = $appServicePath;
            $appServiceFileName = $tableCamelCase."Service.php";
            $parseData = [$appServicePath,$appServiceNamespace,$appServiceFileName];
            var_dump($parseData);
            return $parseData;
        }
        if ($genCodeEnum->is(GenCodeEnum::ApiMapper)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::ApiMapper);
            $appMapperPath = $this->appMapperPath($app);
            $appMapperPath = $appMapperPath .DS. $modulePath;
            $appMapperNamespace = $appMapperPath;
            $appMapperFileName = $tableCamelCase."Mapper.php";
            $parseData = [$appMapperPath,$appMapperNamespace,$appMapperFileName];
            var_dump($parseData);
            return $parseData;
        }

        if ($genCodeEnum->is(GenCodeEnum::ApiValidate)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::ApiValidate);
            $appValidatePath = $this->appValidatePath($app);
            $appValidatePath = $appValidatePath .DS. $modulePath;
            $appValidateNamespace = $appValidatePath;
            $appValidateFileName = $tableCamelCase."Validate.php";
            $parseData = [$appValidatePath,$appValidateNamespace,$appValidateFileName];
            var_dump($parseData);
            return $parseData;
        }

        if ($genCodeEnum->is(GenCodeEnum::ApiModel)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::ApiModel);
            $appModelPath = $this->appModelPath($app);
            $appModelPath = $appModelPath .DS. $modulePath;
            $appModelNamespace = $appModelPath;
            $appModelFileName = $tableCamelCase.".php";
            $parseData = [$appModelPath,$appModelNamespace,$appModelFileName];
            var_dump($parseData);
            return $parseData;
        }


        if ($genCodeEnum->is(GenCodeEnum::UiController)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::UiController);
            $appUiControllerPath = $this->appUiControllerPath($app);
            $appUiControllerPath = $appUiControllerPath .DS. $modulePath;

            //获取接口url组路径
            $uiUrlPath = "/ui/{$module}/$tableSmallCamelCase";
            //视图主路径
            $appUiViewBasePath = $module."/".$tableSmallCamelCase;
            $appUiControllerNamespace = $appUiControllerPath;
            $appUiControllerFileName = $tableCamelCase."Controller.php";
            $parseData = [$appUiControllerPath,$appUiControllerNamespace,$appUiControllerFileName,$appUiViewBasePath,$uiUrlPath];
            var_dump($parseData);
            return $parseData;
        }

        if ($genCodeEnum->is(GenCodeEnum::UiIndexPage)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::UiIndexPage);
            $appUiViewPath = $this->appUiViewPath();
            $appUiViewPath = $appUiViewPath .DS. $modulePath.DS.$tableSmallCamelCase;
            $appUiViewFileName = "index.html";
            $parseData = [$appUiViewPath,$appUiViewFileName];
            return $parseData;
        }

        if ($genCodeEnum->is(GenCodeEnum::UiAddPage)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::UiAddPage);
            $appUiViewPath = $this->appUiViewPath();
            $appUiViewPath = $appUiViewPath .DS. $modulePath.DS.$tableSmallCamelCase;
            $appUiViewFileName = "add.html";
            $parseData = [$appUiViewPath,$appUiViewFileName];
            return $parseData;
        }

        if ($genCodeEnum->is(GenCodeEnum::UiEditPage)){
            //处理该逻辑
            var_dump("处理该逻辑:".GenCodeEnum::UiEditPage);
            $appUiViewPath = $this->appUiViewPath();
            $appUiViewPath = $appUiViewPath .DS. $modulePath.DS.$tableSmallCamelCase;
            $appUiViewFileName = "edit.html";
            $parseData = [$appUiViewPath,$appUiViewFileName];
            return $parseData;
        }
        throw new NyuwaGencodeException("没有匹配的生成文件枚举，请检查...");
    }

    /**
     * 提取代码中用户定义的内容，重新生成的时候重新替换回去
     */
    protected function userCodeCrawl(){
//        $strSubject = "abc【111】abc【222】abc【333】abc";
        $strSubject = <<<EOF

abc【111】abc【222】abc【333】abc
//USER#CODE#START
ssasaf
//USER#CODE#END
abc【111】abc【222】abc【333】abc
//USER#CODE#START
ssasaf2
//USER#CODE#END
abc【111】abc【222】abc【333】abc
//USER#CODE#START
ssasaf1
//USER#CODE#END
EOF;
        $strPattern = "/(?<=\/\/USER#CODE#START)[^\/\/USER#CODE#END]+/";
        $arrMatches = [];
        preg_match_all($strPattern, $strSubject, $arrMatches);
        var_dump($arrMatches);
    }

    /**
     * 写入到文件
     * @param string $stubName 相对模板目录下的具体路径。
     * @param array  $data 需要替换的所有模板内变量
     * @param string $pathname
     * @return mixed
     */
    protected function writeInFile($stubName, $data, $pathname)
    {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $content = $this->getReplacedStub($stubName, $data);

        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }
        return file_put_contents($pathname, $content);
    }

    /**
     * 写入到备份文件
     * @param string $originPathName 目录下的备份文件具体路径。
     * @param string $targetPathName
     * @return mixed
     */
    protected function writeInGencodeBackupFile(string $originPathName, string $targetPathName)
    {
        if (!file_exists($originPathName)){
            throw new NyuwaException("备份源文件不存在,请检查");
        }
        if (!is_dir(dirname($targetPathName))) {
            mkdir(dirname($targetPathName), 0755, true);
        }
        return file_put_contents($targetPathName, file_get_contents($originPathName));
    }

    /**
     * 获取替换后的数据
     * @param string $name 相对模板目录下的具体路径
     * @param array  $data 需要替换的模板内变量
     * @return string
     */
    protected function getReplacedStub($name, $data)
    {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $search = $replace = [];
        foreach ($data as $k => $v) {
            $search[] = "{%{$k}%}";
            $replace[] = $v;
        }
        $stubName = $this->getStub($name);
        if (isset($this->stubList[$stubName])) {
            $stub = $this->stubList[$stubName];
        } else {
            $this->stubList[$stubName] = $stub = file_get_contents($stubName);
        }
        return str_replace($search, $replace, $stub);
    }

    /**
     * 默认curl基础路径
     * @param int $type 1相对项目根目录 0绝对目录，默认1
     * @return string
     */
    protected function appPath($appName = "admin",$type = 1): string
    {
        return ($type == 1 ?"":BASE_PATH .DS) ."app". DS .  $appName;
    }

    /**
     * 获取模板
     * @param $file
     * @return string
     */
    protected function getStub($file){
        return BASE_PATH . DS ."nyuwa". DS . "gencode".DS."stubs".DS.$file.".stub";
    }

    /**
     * 获取api 控制器目录
     * @param string $appName
     * @return string
     */
    protected function appApiControllerPath($appName = "admin",$type = 1){
        return $this->appPath($appName,$type).DS."controller". DS . "api";;
    }

    /**
     * 获取ui 控制器目录
     * @param string $appName
     * @return string
     */
    protected function appUiControllerPath($appName = "adminUi",$type = 1){
        return $this->appPath($appName,$type).DS."controller";
    }

    /**
     * 获取Mapper 目录
     * @param string $appName
     * @return string
     */
    protected function appMapperPath($appName = "admin",$type = 1){
        return $this->appPath($appName,$type).DS."mapper";//. DS . $appName;;
    }
    /**
     * 获取模型 目录
     * @param string $appName
     * @return string
     */
    protected function appModelPath($appName = "admin",$type = 1){
        return $this->appPath($appName,$type).DS."model";//. DS . $appName;;
    }

    /**
     * 获取服务 目录
     * @param string $appName
     * @return string
     */
    protected function appServicePath($appName = "admin",$type = 1){
        return $this->appPath($appName,$type).DS."service";//. DS . $appName;;
    }

    /**
     * 获取验证器 目录
     * @param string $appName
     * @return string
     */
    protected function appValidatePath($appName = "admin",$type = 1){
        return $this->appPath($appName,$type).DS."validate";//. DS . $appName;;
    }


    /**
     * 获取ui 视图目录
     * @param string $appName
     * @return string
     */
    protected function appUiViewPath($appName = "adminUi",$type = 1){
        return $this->appPath($appName,$type).DS."view";;
    }


    /**
     * 获取表字段结构信息
     * @param string $table
     * @return array
     */
    public function getTableDetailInfo(string $table){

        $sql = <<<SQL
SELECT COLUMN_NAME,COLUMN_KEY,ORDINAL_POSITION
	, column_type, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH, data_type,COLUMN_COMMENT
	, CONCAT('"', COLUMN_NAME, '" => "字段 ', column_comment, ":", COLUMN_NAME, '",') as COLUMN_VALIDATE_DESC
	, CONCAT('"', column_comment, '" ,') as COLUMN_COMMENT_DESC
	, CONCAT('"', COLUMN_NAME, '" => "', CASE 
		WHEN data_type = 'varchar'  THEN CONCAT("required|max:", CHARACTER_MAXIMUM_LENGTH)
		WHEN data_type = 'char' THEN 'required|string'
		WHEN data_type = 'bigint' THEN 'required'
		WHEN data_type = 'int' THEN 'required'
		WHEN data_type = 'tinyint' THEN 'required|numeric'
		WHEN data_type = 'timestamp' THEN 'required|date'
	END, '",  //', column_comment) AS "DATA_TYPE_DESC"
FROM information_schema.COLUMNS
WHERE table_name = :table order by `ORDINAL_POSITION` ;
SQL;



        $info = Db::select($sql,['table'=>$table]);
        return $info;
    }

    /**
     * 获取表结构信息
     * @param string $table
     * @return mixed
     */
    public function getTableBaseInfo(string $table){
        $info = Db::select("SELECT * FROM information_schema.TABLES WHERE table_name = :table ;",['table'=>$table]);
        if (count($info) == 1){
            return $info[0];
        }
        throw new NyuwaException("没有查找到表:".$table);
    }

    public function getAllTable(){
        return Db::select("SELECT * FROM information_schema.TABLES where TABLE_TYPE = :key ;",['key'=>"BASE TABLE"]);
    }


    /**
     * 获取ui的参数
     * @param $table
     * @param $tableDetailInfo
     * @param $BasicConfigInfo
     * @return array
     */
    public function getUiParamsInfo($table,$tableDetailInfo, $BasicConfigInfo){

        //重新创建则清理缓存数据。

        $cols = [];
        $cols []= "{type:'checkbox', fixed: 'left'}";
        //关联，字典转换。
        $systemDictDataService = nyuwa_app(SystemDictDataService::class);
        $systemDictFieldService = nyuwa_app(SystemDictFieldService::class);
//        $dictFields = $systemDictFieldService->getTableField($table);//从缓存获取
        //不适用缓存
        $dictFields = $systemDictFieldService->mapper->getDictFieldArrayByTable($table);;
        var_dump("获取存在字典");
        var_dump($dictFields);
        //获取存在字典的

        //add 、edit的表单生成
        $uiFormEditHtmlString = "";//编辑表单的生成
        $uiFormAddHtmlString = "";//添加表单的生成，不包含id表单
        $uiFormJsString = "";
        $uiEditFormString = '{';//赋值编辑内容,不包含字典的初始化

        $xmSelectSetValues = []; // xmselect 赋值

        $tableCenterScoreCase = $BasicConfigInfo['tableCenterScoreCase'];
        $tableBar = "#{$tableCenterScoreCase}-bar";

        //生成前端数据-目前支持单表，字典。
        foreach ($tableDetailInfo as $key => $item){
            //根据字段长度优化限制表单长度，待定。
            $columnName = $item->COLUMN_NAME;
            $columnComment = $item->COLUMN_COMMENT;
            $dataType = $item->DATA_TYPE;
            $characterMaximumLength = $item->CHARACTER_MAXIMUM_LENGTH;
            $isNullable = $item->IS_NULLABLE;


            //列表table cols 生成
            if (!in_array($columnName,$this->ignoreTableFields)){
                if (in_array($columnName,$dictFields)){
                    $str = "{title: '$columnComment',field: '{$columnName}_label',align: 'left'}";
                }else{
                    $str = "{title: '$columnComment',field: '$columnName',align: 'left'}";
                }
                $cols []= $str;
            }


            //表单操作

            //表单生成忽略一些字段
            $ignoreFormFields = $this->ignoreFormFields;
            if (in_array($columnName,$ignoreFormFields)){
                continue;
            }

            if (in_array($columnName,$dictFields)){
                //配置了字典的则使用select
//                $str = "{title: '$columnComment',field: '{$columnName}_label',align: 'center'}";
                $dictField = $systemDictFieldService->getDictFieldByTAndF($table,$columnName);
                $dictDataArr = $systemDictDataService->mapper->getDictDataListByTypeId($dictField['dict_type_id']);
                $html = $this->getReplacedStub("mixins/xmselect",["columnComment"=>$columnComment,"columnName"=>$columnName]);
                $uiFormEditHtmlString .= $html.PHP_EOL;
                $uiFormAddHtmlString .= $html.PHP_EOL;
                $xmSelectName = nyuwa_camelize($columnName,"_",0)."XmSelect";
                //获取xmselect定义名，与字段，进行赋值绑定
                $xmSelectSetValues []= "$xmSelectName.setValue([info.$columnName])";
                //编辑的初始化xmselect 的值
                var_dump($dictField);
                $genFormEnum = GenFormEnum::byValue($dictField['view_type']);
                $js = "";
                if ($genFormEnum->is(GenFormEnum::RADIO_XMSELECT))
                    $js = $this->getReplacedStub("mixins/xmselect-radio-js",["columnComment"=>$columnComment,"columnName"=>$columnName,"columnDictData"=>json_encode($dictDataArr,JSON_UNESCAPED_UNICODE),"xmSelectName"=>$xmSelectName]);
                elseif ($genFormEnum->is(GenFormEnum::CHECKBOX_XMSELECT))
                    $js = $this->getReplacedStub("mixins/xmselect-checkbox-js",["columnComment"=>$columnComment,"columnName"=>$columnName,"columnDictData"=>json_encode($dictDataArr,JSON_UNESCAPED_UNICODE),"xmSelectName"=>$xmSelectName]);
                $uiFormJsString .= $js.PHP_EOL;
                var_dump($js);
            }else{
                if ($columnName == "id"){
                }else{
                    $addHtml = $this->getReplacedStub("mixins/text",["disabled"=>"","columnComment"=>$columnComment,"columnName"=>$columnName]);
                    $uiFormAddHtmlString .= $addHtml.PHP_EOL;
                    $uiFormEditHtmlString .= $addHtml.PHP_EOL;
                }
                $uiEditFormString .= "'{$columnName}':info.{$columnName},";
            }
        }
        $cols []= "{title: '操作',toolbar: '{$tableBar}', fixed: 'right',align: 'center',width: 130}";

        $uiTableColsString = implode(",",$cols);
        $xmSelectSetValuesString = implode(PHP_EOL,$xmSelectSetValues);
        var_dump($uiTableColsString);
        $uiEditFormString .= "}";
        //前端table 对应的组件转换.
        $tableUiComponent = "";

        return [$uiTableColsString,$tableUiComponent,$uiFormEditHtmlString,$uiFormJsString,$uiEditFormString,$uiFormAddHtmlString,$xmSelectSetValuesString];

        //需要添加筛选的字段生成

        //根据数据库字段类型转换成对应的表单操作ui。
        //自定义多种ui操作。


        //生成数据
    }


    /**
     * 对应的单表文件生成
     * @param $table
     * @param $basicConfigInfo
     */
    public function singleTableCreate($table, $module, $backup, $tableDetailInfo, $basicConfigInfo){
        if (!$table) {
            throw new NyuwaException("table name can't empty");
        }
        if (!$module) {
            throw new NyuwaException("module name can't empty");
        }

        $app = $this->defaultCurdAppName;
        if (in_array($app,$this->ignoreApp)){
            throw new NyuwaGencodeException("不能使用禁止的应用名");
        }
        $module = str_replace(['.', '/', '\\'], '/', $module);
        $arr = explode('/', $module);
        $arr = array_filter($arr);//去掉空值,得到可靠的模块名
        if (in_array("admin",$arr)){
            throw new NyuwaGencodeException("不能包含使用禁止的模块名");
        }

        [$apiControllerPath,$apiControllerNamespace,$apiControllerFileName,$apiUrlPath] = $this->getParseCoreKey($app, $module,$table,GenCodeEnum::ApiController());
        [$appServicePath,$appServiceNamespace,$appServiceFileName] = $this->getParseCoreKey($app,$module,$table,GenCodeEnum::ApiService());
        [$appValidatePath,$appValidateNamespace,$appValidateFileName] = $this->getParseCoreKey($app,$module,$table,GenCodeEnum::ApiValidate());
        [$appMapperPath,$appMapperNamespace,$appMapperFileName] = $this->getParseCoreKey($app,$module,$table,GenCodeEnum::ApiMapper());
        [$appModelPath,$appModelNamespace,$appModelFileName] = $this->getParseCoreKey($app,$module,$table,GenCodeEnum::ApiModel());

        //ceshi/api/dis/sss/sdsd/testInfo/index
        [$appUiControllerPath,$appUiControllerNamespace,$appUiControllerFileName,$appUiViewBasePath,$uiUrlPath] = $this->getParseCoreKey("adminUi", $module,$table,GenCodeEnum::UiController());

        [$appUiIndexViewPath,$appUiIndexViewFileName] = $this->getParseCoreKey($app,$module,$table,GenCodeEnum::UiIndexPage());
        [$appUiAddViewPath,$appUiAddViewFileName] = $this->getParseCoreKey($app,$module,$table,GenCodeEnum::UiAddPage());
        [$appUiEditViewPath,$appUiEditViewFileName] = $this->getParseCoreKey($app,$module,$table,GenCodeEnum::UiEditPage());


        //收集所有生成的文件全路径

        $genCodeFiles = [
            $apiControllerPath.DS .$apiControllerFileName,
            $appServicePath.DS .$appServiceFileName,
            $appValidatePath.DS .$appValidateFileName,
            $appMapperPath.DS .$appMapperFileName,
            $appModelPath.DS .$appModelFileName,
            $appUiControllerPath.DS .$appUiControllerFileName,
            $appUiIndexViewPath.DS .$appUiIndexViewFileName,
            $appUiAddViewPath.DS .$appUiAddViewFileName,
            $appUiEditViewPath.DS .$appUiEditViewFileName
        ];
        //是否需要备份，默认需要
        $this->backup($backup,$genCodeFiles);

        //服务端代码基础变量
        $serviceBasicConfig = array_merge($basicConfigInfo,[
            "controllerNamespace"=>$apiControllerNamespace,
            "serviceNamespace"=>$appServiceNamespace,
            "validateNamespace"=>$appValidateNamespace,
            "mapperNamespace"=>$appMapperNamespace,
            "modelNamespace"=>$appModelNamespace,
        ]);
        Log::info("服务端配置：".json_encode($serviceBasicConfig,JSON_UNESCAPED_UNICODE));

        //生成服务器代码
        $this->writeInFile("service/controller",$serviceBasicConfig,$apiControllerPath.DS .$apiControllerFileName);
        var_dump("控制器路径:".$apiControllerPath.DS .$apiControllerFileName);
        $this->writeInFile("service/service",$serviceBasicConfig,$appServicePath.DS .$appServiceFileName);
        var_dump("服务路径:".$appServicePath.DS .$appServiceFileName);

        [$validateCommonParams,$validateList,$validateMessageList,$relationDictFields] = $this->getValidateParamsInfo($table,$tableDetailInfo,$basicConfigInfo);
        [$tableFillableFields] = $this->getModelParamsInfo($table,$tableDetailInfo,$basicConfigInfo);


        $this->writeInFile("service/validate",array_merge($serviceBasicConfig,[
            "validateCommonParams" => $validateCommonParams,
            "validateList" => $validateList,
            "validateMessageList"=>$validateMessageList
        ]),$appValidatePath.DS .$appValidateFileName);
        var_dump("validate路径:".$appValidatePath.DS .$appValidateFileName);
        /**
         * 获取mapper参数
         */
        [$tableSearchPhpColString,$tableSearchHtmlColString] = $this->getMapperParamsInfo($table,$tableDetailInfo,$basicConfigInfo);
        $this->writeInFile("service/mapper",array_merge($serviceBasicConfig,["tableSearchPhpColString"=>$tableSearchPhpColString]),$appMapperPath.DS .$appMapperFileName);
        $this->writeInFile("service/model",array_merge($serviceBasicConfig,["tableFillableFields"=>$tableFillableFields]),$appModelPath.DS .$appModelFileName);


        $genUi = false;
        if ($genUi){

            //自动根据字段注入一些基础的转换字段，如status自动注入
            $systemDictFieldService = nyuwa_app(SystemDictFieldService::class);
            $systemDictTypeService = nyuwa_app(SystemDictTypeService::class);

            $systemDictTypes = $systemDictTypeService->getList(["codes"=>$relationDictFields,"select"=>"id,code"]);
            var_dump($systemDictTypes);
            //要入库的字典信息
            $newDictFieldArr = [];
            foreach ($systemDictTypes as $dictTypeItem){
                $newDictFieldArr []= [
                    "dict_type_id" => $dictTypeItem['id'],
                    "table_name" => $table,
                    "field_name" => $dictTypeItem['code'],
                    "default_val" => "未知",
                    "view_type" => "radio_xmselect",
                ];
            }
            var_dump($newDictFieldArr);
            try {
                $i = $systemDictFieldService->batchSave($newDictFieldArr);
                var_dump($i);
            }catch (\Exception $e){

            }


    //        [$uiTableColsString,$tableUiComponent,$uiFormEditHtmlString,$uiFormJsString,$uiEditFormString,$uiFormAddHtmlString,$xmSelectSetValuesString] = $this->getUiParamsInfo($table,$tableDetailInfo,$basicConfigInfo);


            $systemDictFieldService = nyuwa_app(SystemDictFieldService::class);
    //        $dictFields = $systemDictFieldService->getTableField($table);//从缓存获取
            //不适用缓存
            $dictFields = $systemDictFieldService->mapper->getDictFieldArrayByTable($table);;

            [$uiTableColsString,$tableUiComponent,$tableUiComponentJS] = $this->getUiIndexParamsInfo($table,$tableDetailInfo,$basicConfigInfo,$dictFields);
            [$uiFormAddHtmlString,$uiFormAddJsString] = $this->getUiAddParamsInfo($table,$tableDetailInfo,$basicConfigInfo,$dictFields);
            [$uiFormEditHtmlString,$uiFormEditJsString,$uiFormEditSetValueString,$xmSelectSetValuesString] = $this->getUiEditParamsInfo($table,$tableDetailInfo,$basicConfigInfo,$dictFields);


            //前端控制器基础变量配置
            $uiServiceBasicConfigInfo = array_merge($basicConfigInfo,[
                "controllerNamespace"=>$appUiControllerNamespace,
                "serviceNamespace"=>$appServiceNamespace,
                "validateNamespace"=>$appValidateNamespace,
                "mapperNamespace"=>$appMapperNamespace,
                "modelNamespace"=>$appModelNamespace,
                "appUiViewBasePath"=>$appUiViewBasePath,
            ]);

            //默认adminUi应用作为基础的前端渲染ui，后续考虑多个前端ui
            $this->writeInFile("service/uiController",$uiServiceBasicConfigInfo,$appUiControllerPath.DS .$appUiControllerFileName);


            //生成前端代码
            $this->writeInFile("ui/index",array_merge($basicConfigInfo,[
                "apiUrlPath" => $apiUrlPath,
                "uiUrlPath" => $uiUrlPath,
                "uiTableColsString" =>$uiTableColsString,
                "tableUiComponent" =>$tableUiComponent,
                "tableUiComponentJS" =>$tableUiComponentJS,
                "tableSearchHtmlColString" =>$tableSearchHtmlColString,
            ]),$appUiIndexViewPath.DS .$appUiIndexViewFileName);
            $this->writeInFile("ui/add",array_merge($basicConfigInfo,[
                "apiUrlPath" => $apiUrlPath,
                "uiUrlPath" => $uiUrlPath,
                "uiFormAddHtmlString" => $uiFormAddHtmlString,
                "uiFormAddJsString" => $uiFormAddJsString
            ]),$appUiAddViewPath.DS .$appUiAddViewFileName);
            $this->writeInFile("ui/edit",array_merge($basicConfigInfo,[
                "apiUrlPath" => $apiUrlPath,
                "uiUrlPath" => $uiUrlPath,
                "uiFormEditHtmlString" => $uiFormEditHtmlString,
                "uiFormEditJsString" => $uiFormEditJsString,
                "uiFormEditSetValueString" => $uiFormEditSetValueString,
                "xmSelectSetValuesString" => $xmSelectSetValuesString
            ]),$appUiEditViewPath.DS .$appUiEditViewFileName);
        }

        //创建完毕
        var_dump("创建完毕");

        var_dump("接口路径：".$apiUrlPath);
        var_dump("ui接口路径：".$uiUrlPath);
        var_dump("接口路径：".$apiControllerFileName);
        return [$uiUrlPath,$apiUrlPath];

    }


    public function generateCurdMenu($parentMenuId, $basicConfigInfo, $apiUrlPath, $uiUrlPath){

        if (!$parentMenuId){
            throw new NyuwaException("请输入父菜单id");
        }
        $tableComment = $basicConfigInfo['tableComment'];
        $tableComment = mb_substr($tableComment, -1) == '表' ? mb_substr($tableComment, 0, -1) . '' : $tableComment;
        $menuName = $tableComment;
        $commonApiList = $this->commonApi;
        $commonUiList = [
            ["path"=>"add","name"=>"添加","is_hidden"=>0],
            ["path"=>"edit","name"=>"编辑","is_hidden"=>0],
        ];

        $menuUiIdx = ["path"=>"index","name"=>"","is_hidden"=>1];
        $uiUrl = $uiUrlPath."/".$menuUiIdx['path'];
        $code = str_replace("/",":",trim($uiUrl,"/"));
        $name = $menuName.$menuUiIdx['name'];
        $type = SystemMenu::MENUS_LIST;
        $menuItem = [
            "parent_id"=> $parentMenuId,
            "name"=> $name,
            "route"=> $uiUrl,
            "component"=> "",
            "redirect"=> $uiUrl,
            "code"=> $code,
            "icon"=> "",
            "is_hidden"=> $menuUiIdx['is_hidden'],
            "sort"=> 0,
            "type"=> $type,
            "open_type"=> "_iframe",
        ];
        $menuIdxId = nyuwa_app(SystemMenuService::class)->save($menuItem);


        $icon = "";
        $menuList = [];
        foreach ($commonUiList as $k =>$item){
            $uiUrl = $uiUrlPath."/".$item['path'];
            $code = str_replace("/",":",trim($uiUrl,"/"));
            $name = $menuName.$item['name'];
            $type = SystemMenu::MENUS_LIST;
            $menuItem = [
                "parent_id"=> $parentMenuId,
                "name"=> $name,
                "route"=> $uiUrl,
                "component"=> "",
                "redirect"=> $uiUrl,
                "code"=> $code,
                "icon"=> $icon,
                "is_hidden"=> $item['is_hidden'],
                "sort"=> $k,
                "type"=> $type,
                "open_type"=> "_iframe",
            ];
            $menuList []= $menuItem;

        }
        foreach ($commonApiList as $k =>$item){
            $apiUrl = $apiUrlPath."/".$item['path'];
            $code = str_replace("/",":",trim($apiUrl,"/"));
            $type = SystemMenu::BUTTON;
            $name = $menuName.$item['name'];
            $sort = $k+3;
            $menuItem = [
                "parent_id"=> $menuIdxId,
                "name"=> $name,
                "route"=> $apiUrl,
                "component"=> "",
                "redirect"=> $apiUrl,
                "code"=> $code,
                "icon"=> $icon,
                "sort"=> $sort,
                "type"=> $type,
                "open_type"=> "",
            ];
            $menuList []= $menuItem;
        }

        var_dump(json_encode($menuList,JSON_UNESCAPED_UNICODE));
        foreach ($menuList as $item){
            $bool = nyuwa_app(SystemMenuService::class)->save($item);
            var_dump($bool);
        }

    }

    /**
     * 菜单创建
     */
    public function createMenu($parentMenuId,$menuName,$route,$icon,$type,$sort = 0){
        $code = str_replace("/",":",trim($route,"/"));
        $menu = [
            "parent_id"=> $parentMenuId,
            "name"=> $menuName,
            "route"=> $route,
            "component"=> "",
            "redirect"=> $route,
            "code"=> $code,
            "icon"=> $icon,
            "sort"=> 0,
            "type"=> $type,
            "restful"=> "0"
        ];
        nyuwa_app(SystemMenuService::class)->save($menu);

    }


    //以上操作后续添加反操作，可以一键移除生成的业务代码。开发待定

    //可以把业务梳理出来，用go语言实现一遍，实现表到后端服务层到ui视图层的映射。
    /**
     * 获取验证器需要的参数
     * @param $table
     * @param $BasicConfigInfo
     */
    private function getValidateParamsInfo($table,$tableDetailInfo, $BasicConfigInfo)
    {
        $validateList = [];
        $validateMessageList = [];
        $validateCommonParams = [];
        $relationDictFields = [];
        foreach ($tableDetailInfo as $key => $item){
            $columnName = $item->COLUMN_NAME;
            $columnComment = $item->COLUMN_COMMENT;
            $dataType = $item->DATA_TYPE;
            $characterMaximumLength = $item->CHARACTER_MAXIMUM_LENGTH;
            $isNullable = $item->IS_NULLABLE;


            $validateList[]= $item->DATA_TYPE_DESC;
            $validateMessageList[]= $item->COLUMN_VALIDATE_DESC;

            $initDictFields = $this->initDictFields;
            if (in_array($columnName,$initDictFields)){
                //该字段需要关联字典
                $relationDictFields []= $columnName;
            }

            //表单验证生成忽略一些字段
            $ignoreFormFields = $this->ignoreFormFields;
            if (in_array($columnName,$ignoreFormFields)){
                continue;
            }
            if ($columnName == "id"){
                continue;
            }
            $validateCommonParams []= "'".$columnName."',";
        }
        $validateCommonParams = implode(" ",$validateCommonParams);
        $validateList = implode(PHP_EOL."        ",$validateList);
        $validateMessageList = implode(PHP_EOL."        ",$validateMessageList);
        return [$validateCommonParams,$validateList,$validateMessageList,$relationDictFields];
    }

    /**
     * 模型模板变量获取
     * @param $table
     * @param $tableDetailInfo
     * @param $BasicConfigInfo
     */
    private function getModelParamsInfo($table, $tableDetailInfo, $BasicConfigInfo)
    {
        $tableFillableFields = '';
        $tableAnnotationFields = "";//模型字段注解字段
        foreach ($tableDetailInfo as $key => $item){
            $columnName = $item->COLUMN_NAME;
            $columnComment = $item->COLUMN_COMMENT;
            $dataType = $item->DATA_TYPE;
            $characterMaximumLength = $item->CHARACTER_MAXIMUM_LENGTH;
            $isNullable = $item->IS_NULLABLE;
            if ($columnName == "id"){
                continue;
            }
            $tableFillableFields .= "'{$columnName}',";

        }
        return [$tableFillableFields];
    }

    /**
     * mapper模板变量获取
     * @param $table
     * @param $tableDetailInfo
     * @param $BasicConfigInfo
     */
    private function getMapperParamsInfo($table, $tableDetailInfo, $BasicConfigInfo)
    {
        $tableSearchPhpFields = [];
        $tableSearchHtmlFields = [];
        //获取表存在的索引，根据索引设置查询
        $info = Db::select("SELECT * FROM information_schema.STATISTICS WHERE table_name = :table ;",['table'=>$table]);

        foreach ($info as $key => $item){
            $columnName = $item->COLUMN_NAME;
            if ($columnName == "id"){
                continue;
            }
            $sql = <<<SQL
SELECT COLUMN_NAME,COLUMN_KEY,ORDINAL_POSITION
	, column_type, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH, data_type,COLUMN_COMMENT
FROM information_schema.COLUMNS
WHERE table_name = :table and COLUMN_NAME = :COLUMN_NAME order by `ORDINAL_POSITION` ;
SQL;
            $columnInfo = Db::select($sql,['table'=>$table,"COLUMN_NAME"=>$columnName]);
            var_dump($columnInfo);
            $columnComment = $columnInfo[0]->COLUMN_COMMENT;
            $php = $this->getReplacedStub("mixins/handleSearch-php",["columnComment"=>$columnComment,"columnName"=>$columnName]);
            $html = $this->getReplacedStub("mixins/handleSearch-Tpl",["columnComment"=>$columnComment,"columnName"=>$columnName]);
            $tableSearchPhpFields []= $php;
            $tableSearchHtmlFields []= $html;

        }
        $tableSearchPhpColString = implode(PHP_EOL,$tableSearchPhpFields);
        $tableSearchHtmlColString = implode(PHP_EOL,$tableSearchHtmlFields);
        return [$tableSearchPhpColString,$tableSearchHtmlColString];
    }

    /**
     * ui index 列表变量
     * @param $table
     * @param $tableDetailInfo
     * @param $basicConfigInfo
     */
    private function getUiIndexParamsInfo($table, $tableDetailInfo, $basicConfigInfo,$dictFields)
    {
        $cols []= "{type:'checkbox', fixed: 'left'}";
        //关联，字典转换。
        $tableCenterScoreCase = $basicConfigInfo['tableCenterScoreCase'];
        $tableBar = "#{$tableCenterScoreCase}-bar";
        $colTpls = [];
        $colJs = [];
        //获取存在字典的
        //生成前端数据-目前支持单表，字典。
        foreach ($tableDetailInfo as $key => $item) {
            //根据字段长度优化限制表单长度，待定。
            $columnName = $item->COLUMN_NAME;
            $columnComment = $item->COLUMN_COMMENT;
            $dataType = $item->DATA_TYPE;
            $characterMaximumLength = $item->CHARACTER_MAXIMUM_LENGTH;
            $isNullable = $item->IS_NULLABLE;

            //列表table cols 生成
            if (!in_array($columnName, $this->ignoreTableFields)) {
                if (in_array($columnName, $dictFields)) {
                    if ("status" == $columnName){
                        var_dump("status字段");
                        $str = "{title: '$columnComment',field: '$columnName', templet: '#{$columnName}_tpl', unresize: true}";
                        $html = $this->getReplacedStub("mixins/tableSwitchTpl",["columnComment"=>$columnComment,"columnName"=>$columnName]);
                        $js = $this->getReplacedStub("mixins/tableSwitchJs",["columnComment"=>$columnComment,"columnName"=>$columnName]);
                        $colTpls []= $html;
                        $colJs []= $js;
                    }else{
                        $str = "{title: '$columnComment',field: '{$columnName}_label',align: 'center'}";
                    }
                }else if(preg_match("/^is_*/",$columnName)){
                    //以is开头的字段直接生成switch
                    $str = "{title: '$columnComment',field: '$columnName', templet: '#{$columnName}_tpl', unresize: true}";
                    $html = $this->getReplacedStub("mixins/tableSwitchTpl",["columnComment"=>$columnComment,"columnName"=>$columnName]);
                    $js = $this->getReplacedStub("mixins/tableSwitchJs",["columnComment"=>$columnComment,"columnName"=>$columnName]);
                    $colTpls []= $html;
                    $colJs []= $js;
                }
//                elseif ("status" == $columnName){
//                    var_dump("包含status字段");
//                    //以is开头的字段直接生成switch
//                    $str = "{title: '$columnComment',field: '$columnName', templet: '#{$columnName}_tpl', unresize: true}";
//                    $html = $this->getReplacedStub("mixins/tableSwitchTpl",["columnComment"=>$columnComment,"columnName"=>$columnName]);
//                    $js = $this->getReplacedStub("mixins/tableSwitchJs",["columnComment"=>$columnComment,"columnName"=>$columnName]);
//                    $colTpls []= $html;
//                    $colJs []= $js;
//                }
                else {
                    $str = "{title: '$columnComment',field: '$columnName',align: 'center'}";
                }
                $cols [] = $str;
            }
        }
        $cols []= "{title: '操作',toolbar: '{$tableBar}', fixed: 'right',align: 'center',width: 130}";

        $uiTableColsString = implode(",",$cols);
        $tableUiComponent = implode(PHP_EOL,$colTpls);
        $tableUiComponentJS = implode(PHP_EOL,$colJs);
        return [$uiTableColsString,$tableUiComponent,$tableUiComponentJS];

    }

    /**
     * ui add 变量
     * @param $table
     * @param $tableDetailInfo
     * @param $basicConfigInfo
     */
    private function getUiAddParamsInfo($table, $tableDetailInfo, $basicConfigInfo,$dictFields)
    {
        //关联，字典转换。
        $systemDictFieldService = nyuwa_app(SystemDictFieldService::class);
        $systemDictDataService = nyuwa_app(SystemDictDataService::class);

//        $dictFields = $systemDictFieldService->getTableField($table);//从缓存获取
        //不适用缓存
//        $dictFields = $systemDictFieldService->mapper->getDictFieldArrayByTable($table);

        //add 、edit的表单生成
        $uiFormAddHtmlString = "";//添加表单的生成，不包含id表单
        $uiFormAddJsString  = "";

        $xmSelectSetValues = []; // xmselect 赋值

        foreach ($tableDetailInfo as $key => $item){
            //根据字段长度优化限制表单长度，待定。
            $columnName = $item->COLUMN_NAME;
            $columnComment = $item->COLUMN_COMMENT;
            $dataType = $item->DATA_TYPE;
            $characterMaximumLength = $item->CHARACTER_MAXIMUM_LENGTH;
            $isNullable = $item->IS_NULLABLE;

            //表单操作

            //表单生成忽略一些字段
            $ignoreFormFields = $this->ignoreFormFields;
            if (in_array($columnName,$ignoreFormFields)){
                continue;
            }

            if (in_array($columnName,$dictFields)){
                //配置了字典的则使用select
//                $str = "{title: '$columnComment',field: '{$columnName}_label',align: 'center'}";
                $dictField = $systemDictFieldService->getDictFieldByTAndF($table,$columnName);
                $dictDataArr = $systemDictDataService->mapper->getDictDataListByTypeId($dictField['dict_type_id']);
                $html = $this->getReplacedStub("mixins/xmselect",["columnComment"=>$columnComment,"columnName"=>$columnName]);
                $uiFormAddHtmlString .= $html.PHP_EOL;
                $xmSelectName = nyuwa_camelize($columnName,"_",0)."XmSelect";
                //获取xmselect定义名，与字段，进行赋值绑定
                $xmSelectSetValues []= "$xmSelectName.setValue([info.$columnName])";
                //编辑的初始化xmselect 的值
                var_dump($dictField);
                $genFormEnum = GenFormEnum::byValue($dictField['view_type']);
                $js = "";
                if ($genFormEnum->is(GenFormEnum::RADIO_XMSELECT))
                    $js = $this->getReplacedStub("mixins/xmselect-radio-js",["columnComment"=>$columnComment,"columnName"=>$columnName,"columnDictData"=>json_encode($dictDataArr,JSON_UNESCAPED_UNICODE),"xmSelectName"=>$xmSelectName]);
                elseif ($genFormEnum->is(GenFormEnum::CHECKBOX_XMSELECT))
                    $js = $this->getReplacedStub("mixins/xmselect-checkbox-js",["columnComment"=>$columnComment,"columnName"=>$columnName,"columnDictData"=>json_encode($dictDataArr,JSON_UNESCAPED_UNICODE),"xmSelectName"=>$xmSelectName]);
                $uiFormAddJsString .= $js.PHP_EOL;
                var_dump($js);
            }else{
                if ($columnName == "id"){

                }else{
                    $addHtml = $this->getReplacedStub("mixins/text",["disabled"=>"","columnComment"=>$columnComment,"columnName"=>$columnName]);
                    $uiFormAddHtmlString .= $addHtml.PHP_EOL;
                }
            }
        }

        return [
            $uiFormAddHtmlString,$uiFormAddJsString
        ];
    }

    /**
     * ui edit 变量
     * @param $table
     * @param $tableDetailInfo
     * @param $basicConfigInfo
     */
    private function getUiEditParamsInfo($table, $tableDetailInfo, $basicConfigInfo,$dictFields)
    {

        //重新创建则清理缓存数据。

        //关联，字典转换。
        $systemDictFieldService = nyuwa_app(SystemDictFieldService::class);
        $systemDictDataService = nyuwa_app(SystemDictDataService::class);

        //edit的表单生成
        $uiFormEditHtmlString = "";//编辑表单的生成
        $uiFormEditJsString = "";
        $uiFormEditSetValueString = '{';//赋值编辑内容,不包含字典的初始化

        $xmSelectSetValues = []; // xmselect 赋值

        //生成前端数据-目前支持单表，字典。
        foreach ($tableDetailInfo as $key => $item){
            //根据字段长度优化限制表单长度，待定。
            $columnName = $item->COLUMN_NAME;
            $columnComment = $item->COLUMN_COMMENT;
            $dataType = $item->DATA_TYPE;
            $characterMaximumLength = $item->CHARACTER_MAXIMUM_LENGTH;
            $isNullable = $item->IS_NULLABLE;



            //表单操作

            //表单生成忽略一些字段
            $ignoreFormFields = $this->ignoreFormFields;
            if (in_array($columnName,$ignoreFormFields)){
                continue;
            }

            if (in_array($columnName,$dictFields)){
                //配置了字典的则使用select
                $dictField = $systemDictFieldService->getDictFieldByTAndF($table,$columnName);
                $dictDataArr = $systemDictDataService->mapper->getDictDataListByTypeId($dictField['dict_type_id']);
                $html = $this->getReplacedStub("mixins/xmselect",["columnComment"=>$columnComment,"columnName"=>$columnName]);
                $uiFormEditHtmlString .= $html.PHP_EOL;
                $xmSelectName = nyuwa_camelize($columnName,"_",0)."XmSelect";
                //获取xmselect定义名，与字段，进行赋值绑定
                $xmSelectSetValues []= "$xmSelectName.setValue([info.$columnName])";
                //编辑的初始化xmselect 的值
                $genFormEnum = GenFormEnum::byValue($dictField['view_type']);
                $js = "";
                if ($genFormEnum->is(GenFormEnum::RADIO_XMSELECT))
                    $js = $this->getReplacedStub("mixins/xmselect-radio-js",["columnComment"=>$columnComment,"columnName"=>$columnName,"columnDictData"=>json_encode($dictDataArr,JSON_UNESCAPED_UNICODE),"xmSelectName"=>$xmSelectName]);
                elseif ($genFormEnum->is(GenFormEnum::CHECKBOX_XMSELECT))
                    $js = $this->getReplacedStub("mixins/xmselect-checkbox-js",["columnComment"=>$columnComment,"columnName"=>$columnName,"columnDictData"=>json_encode($dictDataArr,JSON_UNESCAPED_UNICODE),"xmSelectName"=>$xmSelectName]);
                $uiFormEditJsString .= $js.PHP_EOL;
                var_dump($js);
            }else{
                if ($columnName == "id"){
                }else{
                    $addHtml = $this->getReplacedStub("mixins/text",["disabled"=>"","columnComment"=>$columnComment,"columnName"=>$columnName]);
                    $uiFormEditHtmlString .= $addHtml.PHP_EOL;
                }
                $uiFormEditSetValueString .= "'{$columnName}':info.{$columnName},";
            }
        }

        $xmSelectSetValuesString = implode(PHP_EOL,$xmSelectSetValues);
        $uiFormEditSetValueString .= "}";

        return [$uiFormEditHtmlString,$uiFormEditJsString,$uiFormEditSetValueString,$xmSelectSetValuesString];

    }

    public function backup($backup,$genCodeFiles){
        if ($backup){
            foreach ($genCodeFiles as $filePath){
                //判断是否存在该文件，存在则改名，创建文件夹gencodeBackup方便比对
                $bool = file_exists($filePath);
                $pathInfo = pathinfo($filePath);
                $extension = $pathInfo['extension'];
                $filename = $pathInfo['filename'];
                $dirname = $pathInfo['dirname'];

                //只保留当天的最初版本
                $dateFormat = date("Ymd");
                if ($bool){
                    $backupFilePath =  "gencodeBackup".DS.$dateFormat.DS.$dirname.DS.$filename.".".$extension;
                    //如果有存在的不再生成，备份只保留最初的一份
                    if (!file_exists($backupFilePath)){
                        $this->writeInGencodeBackupFile($filePath,$backupFilePath);
                    }
                }
            }
        }
    }
}