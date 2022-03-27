<?php

declare(strict_types=1);

namespace app\admin\mapper\core;


use app\admin\model\core\SystemDictData;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 字典数据表
 * Class SystemDictDataMapper
 * @package app\admin\mapper\core
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
        return $query;
    }
}
