<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemPost;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 岗位表
 * Class SystemPostMapper
 * @package plugin\stone\app\mapper\core
 */
class SystemPostMapper extends AbstractMapper
{
    /**
     * @var SystemPost
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemPost::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['name'])) {
            $query->where('name', 'like', '%'.$params['name'].'%');
        }
        if (isset($params['code'])) {
            $query->where('code', $params['code']);
        }
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }
        return $query;
    }
}
