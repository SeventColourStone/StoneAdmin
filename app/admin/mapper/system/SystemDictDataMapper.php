<?php


namespace app\admin\mapper\system;


use app\admin\model\system\SystemDictData;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

class SystemDictDataMapper extends AbstractMapper
{
    /**
     * @var SystemDictData
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemDictData::class;
    }

    /**
     * 根据字典类型id获取字典表数据
     * @param $typeId
     */
    public function getDictDataListByTypeId($typeId){
        return $this->model::query()
            ->where("type_id",$typeId)
            ->where("status",0)
            ->orderBy("sort")
            ->get(['label as name','value','code'])->toArray();
    }
    /**
     * 根据字典类型id获取字典表label
     * @param $typeId
     */
    public function getLabelByTypeIdAndVal($typeId,$val){
        return $this->model::query()
            ->where("type_id",$typeId)
            ->where("value",$val)
            ->where("status",0)
            ->value('label');
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['type_id'])) {
            $query->where('type_id', $params['type_id']);
        }
        if (isset($params['code'])) {
            $query->where('code', $params['code']);
        }
        if (isset($params['value'])) {
            $query->where('value', 'like', '%'.$params['value'].'%');
        }
        if (isset($params['label'])) {
            $query->where('label', 'like', '%'.$params['label'].'%');
        }
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        return $query;
    }
}
