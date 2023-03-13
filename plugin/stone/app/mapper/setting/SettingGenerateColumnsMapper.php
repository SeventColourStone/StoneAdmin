<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\setting;


use plugin\stone\app\model\setting\SettingGenerateColumns;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 代码生成业务字段信息表
 * Class SettingGenerateColumnsMapper
 * @package plugin\stone\app\mapper\core
 */
class SettingGenerateColumnsMapper extends AbstractMapper
{
    /**
     * @var SettingGenerateColumns
     */
    public $model;

    public function assignModel()
    {
        $this->model = SettingGenerateColumns::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if ($params['table_id'] ?? false) {
            $query->where('table_id', (int) $params['table_id']);
        }
        return $query;
    }
}
