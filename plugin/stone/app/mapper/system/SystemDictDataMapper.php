<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemDictData;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 字典数据表
 * Class SystemDictDataMapper
 * @package plugin\stone\app\mapper\core
 */
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
