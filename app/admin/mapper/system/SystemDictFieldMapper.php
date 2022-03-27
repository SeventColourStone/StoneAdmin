<?php


namespace app\admin\mapper\system;


use app\admin\model\system\SystemDictField;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

class SystemDictFieldMapper extends AbstractMapper
{
    /**
     * @var SystemDictField
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemDictField::class;
    }


    /**
     * 根据table 跟 表字段获取一个dict field
     * @param $table_name
     * @return array
     */
    public function getDictFieldByTAndF($tableName,$fieldName){
        return $this->model::query()
            ->where('table_name', $tableName)
            ->where('field_name', $fieldName)->first([
                'dict_type_id', 'table_name', 'field_name', 'default_val','view_type', 'remark'
            ])->toArray();
    }

    /**
     * 根据table 获取一组 dict field
     * @param $table_name
     * @return array
     */
    public function getDictFieldArrayByTable($tableName){
        return $this->model::query()
            ->where('table_name', $tableName)
            ->orderByDesc('dict_type_id')
            ->pluck('field_name')->toArray();
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['table_name']) && !empty($params['table_name'])) {
            $query->where('table_name', $params['table_name']);
        }
        if (isset($params['field_name']) && !empty($params['field_name'])) {
            $query->where('field_name', $params['field_name']);
        }
        if (isset($params['dict_type_id']) && !empty($params['dict_type_id'])) {
            $query->where('dict_type_id', $params['dict_type_id']);
        }

        return $query;
    }
}
