<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemNotice;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 系统公告表
 * Class SystemNoticeMapper
 * @package plugin\stone\app\mapper\core
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
