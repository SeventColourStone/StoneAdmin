<?php

declare(strict_types=1);

namespace app\admin\mapper\test;


use app\admin\model\test\TestInfo;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 测试表
 * Class TestInfoMapper
 * @package app\admin\mapper\test
 */
class TestInfoMapper extends AbstractMapper
{
    /**
     * @var TestInfo
     */
    public $model;

    public function assignModel()
    {
        $this->model = TestInfo::class;
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
