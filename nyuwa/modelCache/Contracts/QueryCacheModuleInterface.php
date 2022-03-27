<?php


namespace nyuwa\modelCache\Contracts;


interface QueryCacheModuleInterface
{
    /**
     * 为查询生成唯一缓存键。
     *
     * @param  string  $method
     * @param  string|null  $id
     * @param  string|null  $appends
     * @return string
     */
    public function generatePlainCacheKey(string $method = 'get', string $id = null, string $appends = null): string;

    /**
     * 获取查询缓存回调。
     *
     * @param  string  $method
     * @param  array|string  $columns
     * @param  string|null  $id
     * @return \Closure
     */
    public function getQueryCacheCallback(string $method = 'get', $columns = ['*'], string $id = null);
}
