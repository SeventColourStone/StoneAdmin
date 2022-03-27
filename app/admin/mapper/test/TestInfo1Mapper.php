<?php

declare(strict_types=1);

namespace app\admin\mapper\test;


use app\admin\model\test\TestInfo1;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 测试表
 * Class TestInfo1Mapper
 * @package app\admin\mapper\test
 */
class TestInfo1Mapper extends AbstractMapper
{
    /**
     * @var TestInfo1
     */
    public $model;

    public function assignModel()
    {
        $this->model = TestInfo1::class;
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
