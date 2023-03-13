<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemDictType;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 字典类型表
 * Class SystemDictTypeMapper
 * @package plugin\stone\app\mapper\core
 */
class SystemDictTypeMapper extends AbstractMapper
{
    /**
     * @var SystemDictType
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemDictType::class;
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
