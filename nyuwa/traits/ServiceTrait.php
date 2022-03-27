<?php


namespace nyuwa\traits;


use Illuminate\Database\Eloquent\Collection;
use nyuwa\abstracts\AbstractMapper;
use nyuwa\NyuwaModel;

trait ServiceTrait
{

    /**
     * @var AbstractMapper
     */
    public $mapper;

    /**
     * 获取列表数据
     * @param array|null $params
     * @return array
     */
    public function getList(?array $params = null): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = false;
        return $this->mapper->getList($params);
    }

    /**
     * 从回收站过去列表数据
     * @param array|null $params
     * @return array
     */
    public function getListByRecycle(?array $params = null): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = true;
        return $this->mapper->getList($params);
    }

    /**
     * 获取列表数据（带分页）
     * @param array|null $params
     * @return array
     */
    public function getPageList(?array $params = null): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        return $this->mapper->getPageList($params);
    }

    /**
     * 从回收站获取列表数据（带分页）
     * @param array|null $params
     * @return array
     */
    public function getPageListByRecycle(?array $params = null): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = true;
        return $this->mapper->getPageList($params);
    }

    /**
     * 获取树列表
     * @param array|null $params
     * @return array
     */
    public function getTreeList(?array $params = null): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = false;
        $params['is_hidden'] = 1;
        return $this->mapper->getTreeList($params);
    }

    /**
     * 从回收站获取树列表
     * @param array|null $params
     * @return array
     */
    public function getTreeListByRecycle(?array $params = null): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = true;
        return $this->mapper->getTreeList($params);
    }

    /**
     * 新增数据
     * @param array $data
     * @return string
     */
    public function save(array $data): string
    {
        return $this->mapper->save($data);
    }

    /**
     * 批量新增
     * @param array $collects
     * @Transaction
     * @return bool
     */
    public function batchSave(array $collects): bool
    {
        foreach ($collects as $collect) {
            $this->mapper->save($collect);
        }
        return true;
    }

    /**
     * 读取一条数据
     * @param string $id
     * @return NyuwaModel|null
     */
    public function read(string $id): ?NyuwaModel
    {
        return $this->mapper->read($id);
    }

    /**
     * Description:获取单个值
     * User:mike
     * @param array $condition
     * @param string $columns
     */
    public function value(array $condition, string $columns = 'id')
    {
        return $this->mapper->value($condition, $columns);
    }

    /**
     * Description:获取单列值
     * User:mike
     * @param array $condition
     * @param string $columns
     * @return array|null
     */
    public function pluck(array $condition, string $columns = 'id'): array
    {
        return $this->mapper->pluck($condition, $columns);
    }

    /**
     * 从回收站读取一条数据
     * @param string $id
     * @return NyuwaModel
     * @noinspection PhpUnused
     */
    public function readByRecycle(string $id): NyuwaModel
    {
        return $this->mapper->readByRecycle($id);
    }

    /**
     * 单个或批量软删除数据
     * @param string $ids
     * @return bool
     */
    public function delete(String $ids): bool
    {
        return !empty($ids) && $this->mapper->delete(explode(',', $ids));
    }

    /**
     * 更新一条数据
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        return $this->mapper->update($id, $data);
    }

    /**
     * 按条件更新数据
     * @param array $condition
     * @param array $data
     * @return bool
     */
    public function updateByCondition(array $condition, array $data): bool
    {
        return $this->mapper->updateByCondition($condition, $data);
    }

    /**
     * 单个或批量真实删除数据
     * @param string $ids
     * @return bool
     */
    public function realDelete(string $ids): bool
    {
        return !empty($ids) && $this->mapper->realDelete(explode(',', $ids));
    }

    /**
     * 单个或批量从回收站恢复数据
     * @param string $ids
     * @return bool
     */
    public function recovery(string $ids): bool
    {
        return !empty($ids) && $this->mapper->recovery(explode(',', $ids));
    }

    /**
     * 单个或批量禁用数据
     * @param string $ids
     * @param string $field
     * @return bool
     */
    public function disable(string $ids, string $field = 'status'): bool
    {
        return !empty($ids) && $this->mapper->disable(explode(',', $ids), $field);
    }

    /**
     * 单个或批量启用数据
     * @param string $ids
     * @param string $field
     * @return bool
     */
    public function enable(string $ids, string $field = 'status'): bool
    {
        return !empty($ids) && $this->mapper->enable(explode(',', $ids), $field);
    }

    /**
     * 修改数据状态
     * @param string $id
     * @param string $value
     * @return bool
     */
    public function changeStatus(string $id, string $value): bool
    {
        if ($value === '0') {
            $this->mapper->enable([$id]);
            return true;
        } else if ($value === '1') {
            $this->mapper->disable([$id]);
            return true;
        } else {
            return false;
        }
    }

//    /**
//     * 导出数据
//     * @param array $params
//     * @param string|null $dto
//     * @param string|null $filename
//     * @return ResponseInterface
//     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
//     */
//    public function export(array $params, ?string $dto, string $filename = null):ResponseInterface
//    {
//        if (empty($dto)) {
//            return make(MineResponse::class)->error('导出未指定DTO');
//        }
//
//        if (empty($filename)) {
//            $filename = $this->mapper->getModel()->getTable();
//        }
//
//        $collection = new MineCollection();
//
//        return $collection->export($dto, $filename, $this->mapper->getList($params));
//    }

//    /**
//     * 数据导入
//     * @param string $dto
//     * @param \Closure|null $closure
//     * @return bool
//     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
//     * @Transaction
//     */
//    public function import(string $dto, ?\Closure $closure = null): bool
//    {
//        return $this->mapper->import($dto, $closure);
//    }

    /**
     * 数组数据转分页数据显示
     * @param array|null $params
     * @param string $pageName
     * @return array
     */
    public function getArrayToPageList(?array $params = [], string $pageName = 'page'): array
    {
        $collect = $this->handleArraySearch(nyuwa_collect($this->getArrayData($params)), $params);

        $pageSize = NyuwaModel::PAGE_SIZE;
        $page = 1;

        if ($params[$pageName] ?? false) {
            $page = (int) $params[$pageName];
        }

        if ($params['pageSize'] ?? false) {
            $pageSize = (int) $params['pageSize'];
        }

        $data = $collect->forPage($page, $pageSize)->toArray();

        return [
            'items' => $this->getCurrentArrayPageBefore($data, $params),
            'pageInfo' => [
                'total' => $collect->count(),
                'currentPage' => $page,
                'totalPage' => ceil($collect->count() / $pageSize)
            ]
        ];
    }

    /**
     * 数组数据搜索器
     * @param Collection $collect
     * @param array $params
     * @return Collection
     */
    protected function handleArraySearch(Collection $collect, array $params)
    {
        return $collect;
    }

    /**
     * 数组当前页数据返回之前处理器，默认对key重置
     * @param array $data
     * @param array $params
     * @return array
     */
    protected function getCurrentArrayPageBefore(array &$data, array $params = []): array
    {
        sort($data);
        return $data;
    }

    /**
     * 设置需要分页的数组数据
     * @param array $params
     * @return array
     */
    protected function getArrayData(array $params = []): array
    {
        return [];
    }
}
