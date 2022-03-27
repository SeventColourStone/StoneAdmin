<?php

declare(strict_types=1);

namespace app\admin\mapper\core;


use app\admin\model\core\SystemPost;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 岗位表
 * Class SystemPostMapper
 * @package app\admin\mapper\core
 */
class SystemPostMapper extends AbstractMapper
{
    /**
     * @var SystemPost
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemPost::class;
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
