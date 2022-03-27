<?php

declare(strict_types=1);

namespace app\admin\mapper\generate;


use app\admin\model\generate\GenerateColumns;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 代码生成业务字段信息表
 * Class GenerateColumnsMapper
 * @package app\admin\mapper\generate
 */
class GenerateColumnsMapper extends AbstractMapper
{
    /**
     * @var GenerateColumns
     */
    public $model;

    public function assignModel()
    {
        $this->model = GenerateColumns::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['table_id'])) {
            $query->where('table_id',$params['table_id']);
        }
        return $query;
    }
}
