<?php


namespace app\admin\service\system;


use app\admin\mapper\system\SystemDictFieldMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;
use support\Cache;

class SystemDictFieldService extends AbstractService
{
    /**
     * @Inject
     * @var SystemDictFieldMapper
     */
    public $mapper;


    /**
     * @Inject
     * @var SystemDictDataService
     */
    public $systemDictDataService;


    /**
     * 缓存
     */
    public function getDictFieldByTAndF($tableName,$fieldName){
        //存在值
        $key = "dict_data_label_".$tableName."_".$fieldName;
        $info = Cache::get($key);
        if (!$info) {
            $info = $this->mapper->getDictFieldByTAndF($tableName, $fieldName);
            if ($info){
                Cache::set($key,$info);
                return $info;
            }else{
                return null;
            }
        }
        return $info;
    }

    /**
     * 根据表名、字段名、值获取对应的转换文本。
     */
    public function parseDictLabel($tableName,$fieldName,$val){
        //存在值
        $key = "dict_data_label_".$tableName."_".$fieldName."_".$val;
        $label = Cache::get($key);
        if (!$label){
            $info = $this->mapper->getDictFieldByTAndF($tableName,$fieldName);
            //数据库获取不到，取默认值代替
            $label =  $this->systemDictDataService->mapper->getLabelByTypeIdAndVal($info['dict_type_id'],$val);
            if ($label){
                Cache::set($key,$label);
            }else{
                Cache::set($key,$info['default_val']);
                $label = $info['default_val'];
            }
            return $label;
        }
        return $label;
    }

    public function getTableField($tableName){
        $key = "dict_field_".$tableName;
        $val = Cache::get($key);
        if (!$val){
            $info = $this->mapper->getDictFieldArrayByTable($tableName);
            Cache::set($key,$info);
            $val = $info;
        }
        return $val;
    }

}
