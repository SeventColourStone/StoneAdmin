<?php

declare(strict_types=1);

namespace app\admin\mapper\generate;


use app\admin\model\generate\GenerateTables;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 业务表生成器表
 * Class GenerateTablesMapper
 * @package app\admin\mapper\generate
 */
class GenerateTablesMapper extends AbstractMapper
{
    /**
     * @var GenerateTables
     */
    public $model;

    public function assignModel()
    {
        $this->model = GenerateTables::class;
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
