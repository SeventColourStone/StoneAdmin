<?php

declare(strict_types=1);

namespace app\admin\mapper\core;


use app\admin\model\core\SystemNotice;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 系统公告表
 * Class SystemNoticeMapper
 * @package app\admin\mapper\core
 */
class SystemNoticeMapper extends AbstractMapper
{
    /**
     * @var SystemNotice
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemNotice::class;
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
