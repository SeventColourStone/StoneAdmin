<?php

declare(strict_types=1);

namespace app\admin\mapper\setting;


use app\admin\model\setting\SettingCrontabLog;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 定时任务执行日志表
 * Class SettingCrontabLogMapper
 * @package app\admin\mapper\core
 */
class SettingCrontabLogMapper extends AbstractMapper
{
    /**
     * @var SettingCrontabLog
     */
    public $model;

    public function assignModel()
    {
        $this->model = SettingCrontabLog::class;
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
