<?php


namespace plugin\stone\app\service\setting;


use support\Redis;

class CacheMonitorService
{

    /**
     * @return array
     */
    public function getCacheServerInfo(): array
    {
        $info = Redis::instance()->client()->info();

        $keys = Redis::keys(config('cache.default.prefix').'*');

        return [
            'keys'      => &$keys,
            'server'    => [
                'version'           => &$info['redis_version'],
                'redis_mode'        => ($info['redis_mode'] === 'standalone') ? '单机' : '集群',
                'run_days'          => &$info['uptime_in_days'],
                'aof_enabled'       => ($info['aof_enabled'] == 0) ? '关闭' : '开启',
                'use_memory'        => &$info['used_memory_human'],
                'port'              => &$info['tcp_port'],
                'clients'           => &$info['connected_clients'],
                'expired_keys'      => &$info['expired_keys'],
                'sys_total_keys'    => count($keys)
            ]
        ];
    }

    /**
     * 查看缓存内容
     * @param string $key
     * @return string
     */
    public function view(string $key): string
    {
        return Redis::get($key);
    }

    /**
     * 删除一个缓存
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        return Redis::del($key) > 0;
    }

    /**
     * 清空所有缓存
     * @return bool
     */
    public function clear(): bool
    {
        return Redis::instance()->client()->flushDB();
    }
}