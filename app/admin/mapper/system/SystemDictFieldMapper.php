<?php

declare(strict_types=1);

namespace app\admin\mapper\system;


use app\admin\model\system\SystemDictField;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 字典表转换
 * Class SystemDictFieldMapper
 * @package app\admin\mapper\core
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
