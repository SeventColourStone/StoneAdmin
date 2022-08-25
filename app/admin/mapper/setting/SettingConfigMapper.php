<?php

declare(strict_types=1);

namespace app\admin\mapper\setting;


use app\admin\model\setting\SettingConfig;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

/**
 * 参数配置信息表
 * Class SettingConfigMapper
 * @package app\admin\mapper\core
 */
class SettingConfigMapper extends AbstractMapper
{
    /**
     * @var SettingConfig
     */
    public $model;

    public function assignModel()
    {
        $this->model = SettingConfig::class;
    }


    /**
     * 按组获取配置
     * @param string $groupName
     * @return array
     */
    public function getConfigByGroup(string $groupName): array
    {
        return $this->model::query()
            ->where('group_name', $groupName)
            ->orderByDesc('sort')->get([
                'group_name', 'name', 'key', 'value', 'sort', 'remark'
            ])->toArray();
    }

    /**
     * 按Key获取配置
     * @param string $key
     * @return array
     */
    public function getConfigByKey(string $key): array
    {
        $model = $this->model::query()->find($key, [
            'group_name', 'name', 'key', 'value', 'sort', 'remark'
        ]);
        return $model ? $model->toArray() : [];
    }

    /**
     * 更新配置
     * @param $key
     * @param $value
     * @return bool
     */
    public function updateConfig($key, $value): bool
    {
        return $this->model::query()->where('key', $key)->update(['value' => $value]) > 0;
    }

//    public function save(array $data)
//    {
//        $this->filterExecuteAttributes($data);
//        $model = $this->model::create($data);
//        return ($model->{$model->getKeyName()}) ? 1 : 0;
//    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        if (isset($params['key'])) {//搜索配置键名
            $query->where('key', $params['key']);
        }

        if (isset($params['group_name'])) {//搜索组名称
            $query->where('group_name', $params['group_name']);
        }
        return $query;
    }
     */
}
