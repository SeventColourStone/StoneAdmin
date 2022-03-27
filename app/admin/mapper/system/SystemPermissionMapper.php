<?php


namespace app\admin\mapper\system;


use app\admin\model\system\SystemPermission;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

class SystemPermissionMapper extends AbstractMapper
{
    /**
     * @var SystemPermission
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemPermission::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['route'])) {
            $query->where('route',"like", $params['route']);
        }
        if (isset($params['code'])) {
            $query->where('code', $params['code']);
        }

        return $query;
    }
}
