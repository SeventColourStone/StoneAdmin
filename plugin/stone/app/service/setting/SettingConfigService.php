<?php

declare(strict_types=1);

namespace plugin\stone\app\service\setting;


use plugin\stone\app\mapper\setting\SettingConfigMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;
use support\Redis;

class SettingConfigService extends AbstractService
{
    /**
     * @var SettingConfigMapper
     */
    public $mapper;


    public function __construct(SettingConfigMapper $mapper)
    {
        $this->mapper = $mapper;
        $this->setCacheGroupName($this->prefix . 'configGroup:');
        $this->setCacheName($this->prefix . 'config:');
    }

    /**
     * @var string
     */
    protected $prefix = "STONE_ADMIN_";

    /**
     * @var string
     */
    protected $cacheGroupName;

    /**
     * @var string
     */
    protected $cacheName;



    /**
     * 获取系统组配置
     * @return array
     */
    public function getSystemGroupConfig(): array
    {
        return $this->mapper->getConfigByGroup('system');
    }

    /**
     * 获取扩展组配置
     * @return array
     */
    public function getExtendGroupConfig(): array
    {
        return $this->mapper->getConfigByGroup('extend');
    }

    /**
     * 按组获取配置，并缓存
     * @param string $groupName
     * @return array
     */
    public function getConfigByGroup(string $groupName): array
    {
        if (empty($groupName)) return [];
        $cacheKey = $this->getCacheGroupName() . $groupName;
        if ($data = Redis::get($cacheKey)) {
            return unserialize($data);
        } else {
            $data = $this->mapper->getConfigByGroup($groupName);
            if ($data) {
                Redis::set($cacheKey, serialize($data));
                return $data;
            }
            return [];
        }
    }

    /**
     * 按key获取配置，并缓存
     * @param string $key
     * @return array
     */
    public function getConfigByKey(string $key): array
    {
        if (empty($key)) return [];
        $cacheKey = $this->getCacheName() . $key;
        if (($data = Redis::get($cacheKey))) {
            return unserialize($data);
        } else {
            $data = $this->mapper->getConfigByKey($key);
            if ($data) {
                Redis::set($cacheKey, serialize($data));
                return $data;
            }
            return [];
        }
    }

    /**
     * 清除缓存
     * @return bool
     */
    public function clearCache(): bool
    {
        $groupCache = Redis::keys($this->getCacheGroupName().'*');
        $keyCache = Redis::keys($this->getCacheName().'*');
        foreach ($groupCache as $item) {
            Redis::del($item);
        }

        foreach($keyCache as $item) {
            Redis::del($item);
        }

        return true;
    }

    /**
     * 保存系统配置组
     * @param array $data
     * @return bool
     */
    public function saveSystemConfig(array $data): bool
    {
        foreach ($data as $key => $value) {
            $this->mapper->updateConfig($key, $value);
        }
        $this->clearCache();
        return true;
    }

    /**
     * 更新配置
     * @param $data
     * @return bool
     */
    public function updated($data): bool
    {
        return $this->mapper->updateConfig($data['key'], $data['value']);
    }

    /**
     * @return string
     */
    public function getCacheGroupName(): string
    {
        return $this->cacheGroupName;
    }

    /**
     * @param string $cacheGroupName
     */
    public function setCacheGroupName(string $cacheGroupName): void
    {
        $this->cacheGroupName = $cacheGroupName;
    }

    /**
     * @return string
     */
    public function getCacheName(): string
    {
        return $this->cacheName;
    }

    /**
     * @param string $cacheName
     */
    public function setCacheName(string $cacheName): void
    {
        $this->cacheName = $cacheName;
    }

}
