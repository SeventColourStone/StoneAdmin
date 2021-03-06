<?php


namespace app\admin\mapper\system;


use app\admin\model\system\SettingConfig;
use nyuwa\abstracts\AbstractMapper;

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

    public function save(array $data): string
    {
        $this->filterExecuteAttributes($data);
        $model = $this->model::create($data);
        return ($model->{$model->getKeyName()}) ? 1 : 0;
    }
}
