<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\setting;


use plugin\stone\app\model\setting\SettingCrontab;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 定时任务信息表
 * Class SettingCrontabMapper
 * @package plugin\stone\app\mapper\core
 */
class SettingCrontabMapper extends AbstractMapper
{
    /**
     * @var SettingCrontab
     */
    public $model;

    public function assignModel()
    {
        $this->model = SettingCrontab::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        return $query;
    }
     */
}
