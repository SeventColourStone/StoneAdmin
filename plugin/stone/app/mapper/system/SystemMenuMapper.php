<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemMenu;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 菜单表
 * Class SystemMenuMapper
 * @package plugin\stone\app\mapper\core
 */
class SystemMenuMapper extends AbstractMapper
{
    /**
     * @var SystemMenu
     */
    public $model;

    /**
     * 查询的菜单字段
     * @var string[]
     */
    public $menuField = [
        'id',
        'parent_id',
        'name',
        'code',
        'icon',
        'route',
        'is_hidden',
        'component',
        'redirect',
        'type'
    ];

    public function assignModel()
    {
        $this->model = SystemMenu::class;
    }

    /**
     * 获取超级管理员（创始人）的菜单
     * @return array
     */
    public function getSuperAdminMenu(): array
    {
        return $this->model::query()
            ->select(["id","parent_id","level","name as title","icon","redirect as href","type","open_type as openType"])
            ->where('status', $this->model::ENABLE)
            ->orderBy('sort', 'asc')
            ->get()->toTree();
    }
    /**
     * 通过菜单ID列表获取菜单数据
     * @param array $ids
     * @return array
     */
    public function getMenuByIds(array $ids): array
    {
        return $this->model::query()
            ->select(["id","parent_id","level","name as title","icon","redirect as href","type","open_type as openType"])
            ->whereIn('id', $ids)
            ->where('status', $this->model::ENABLE)
            ->orderBy('sort', 'asc')
            ->get()->toTree();
    }

    /**
     * 获取超级管理员（创始人）的路由菜单
     * @return array
     */
    public function getSuperAdminRouters(): array
    {
        return $this->model::query()
            ->select($this->menuField)
            ->where('status', $this->model::ENABLE)
            ->orderBy('sort', 'desc')
            ->get()->sysMenuToRouterTree();
    }

    /**
     * 之后用作route前后端分离
     * 通过菜单ID列表获取路由数据
     * @param array $ids
     * @return array
     */
    public function getRoutersByIds(array $ids): array
    {
        return $this->model::query()
            ->whereIn('id', $ids)
            ->where('status', $this->model::ENABLE)
            ->orderBy('sort', 'desc')
            ->select(...$this->menuField)
            ->get()->sysMenuToRouterTree();
    }

    /**
     * 获取前端选择树
     * @return array
     */
    public function getSelectTree(): array
    {
        return $this->model::query()->select(['id', 'parent_id', 'id AS value', 'name AS label'])
            ->where('status', $this->model::ENABLE)
            ->orderBy('sort', 'desc')
            ->get()->toTree();
    }

    /**
     * 查询菜单code
     * @param array|null $ids
     * @return array
     */
    public function getMenuCode(array $ids = null): array
    {
        return $this->model::query()->whereIn('id', $ids)->pluck('code')->toArray();
    }

    /**
     * 通过 code 查询菜单名称
     * @param string $code
     * @return string
     */
    public function findNameByCode(string $code): ?string
    {
        return $this->model::query()->where('code', $code)->value('name');
    }

    /**
     * 单个或批量真实删除数据
     * @param array $ids
     * @return bool
     * @Transaction
     * @DeleteCache("loginInfo:*")
     */
    public function realDelete(array $ids): bool
    {
        foreach ($ids as $id) {
            $model = $this->model::withTrashed()->find($id);
            if ($model) {
                $model->roles()->detach();
                $model->forceDelete();
            }
        }
        return true;
    }

    /**
     * 更新菜单
     * @param string $id
     * @param array $data
     * @return bool
     * @DeleteCache("loginInfo:*")
     */
    public function update(string $id, array $data): bool
    {
        return parent::update($id, $data);
    }

    /**
     * 逻辑删除菜单
     * @param array $ids
     * @return bool
     * @DeleteCache("loginInfo:*")
     */
    public function delete(array $ids): bool
    {
        return parent::delete($ids);
    }

    /**
     * 通过 route 查询菜单
     * @param string $route
     */
    public function findMenuByRoute(string $route)
    {
        return $this->model::query()->where('route', $route)->first();
    }

    /**
     * 查询菜单code
     * @param array|null $ids
     * @return array
     */
    public function getMenuName(array $ids = null): array
    {
        return $this->model::withTrashed()->whereIn('id', $ids)->pluck('name')->toArray();
    }

    /**
     * @param string $id
     * @return bool
     */
    public function checkChildrenExists(string $id): bool
    {
        return $this->model::withTrashed()->where('parent_id', $id)->exists();
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['is_hidden'])) {
            $query->where('is_hidden', $params['is_hidden']);
        }
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (isset($params['name'])) {
            $query->where('name', 'like', '%'.$params['name'].'%');
        }

        if (isset($params['type'])) {
            $query->whereIn('type', array_filter(explode(",",$params['type'])));
        }
        if (isset($params['id'])) {
            $query->whereIn('id', array_filter(explode(",",$params['id'])));
        }
        return $query;
    }
}
