<?php


namespace app\admin\mapper\system;


use app\admin\model\system\SystemDept;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\abstracts\AbstractMapper;

class SystemDeptMapper extends AbstractMapper
{
    /**
     * @var SystemDept
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemDept::class;
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
            ->userDataScope()
            ->get()->toTree();
    }

    /**
     * 查询部门名称
     * @param array|null $ids
     * @return array
     */
    public function getDeptName(array $ids = null): array
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
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['name'])) {
            $query->where('name', 'like', '%'.$params['name'].'%');
        }
        return $query;
    }
}