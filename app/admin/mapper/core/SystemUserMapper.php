<?php

declare(strict_types=1);

namespace app\admin\mapper\core;


use app\admin\model\core\SystemUser;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 用户表
 * Class SystemUserMapper
 * @package app\admin\mapper\core
 */
class SystemUserMapper extends AbstractMapper
{
    /**
     * @var SystemUser
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemUser::class;
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
