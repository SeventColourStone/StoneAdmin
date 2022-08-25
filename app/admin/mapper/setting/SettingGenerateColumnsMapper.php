<?php

declare(strict_types=1);

namespace app\admin\mapper\setting;


use app\admin\model\setting\SettingGenerateColumns;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 代码生成业务字段信息表
 * Class SettingGenerateColumnsMapper
 * @package app\admin\mapper\core
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
