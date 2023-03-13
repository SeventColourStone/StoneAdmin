<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemDictField;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 字典表转换
 * Class SystemDictFieldMapper
 * @package plugin\stone\app\mapper\core
 */
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
