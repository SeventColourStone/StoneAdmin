<?php


namespace nyuwa\traits;


use Illuminate\Database\Eloquent\Collection;
use nyuwa\abstracts\AbstractMapper;
use nyuwa\NyuwaCollection;
use nyuwa\NyuwaModel;
use nyuwa\NyuwaResponse;
use support\Cache;
use support\Redis;

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
            if (is_string($params['select'])){
                $params['select'] = explode(',', $params['select']);
            }
        }
        $params['recycle'] = false;
        return $this->mapper->getList($params);

//        if ($params['select'] ?? null) {
//            $params['select'] = explode(',', $params['select']);
//        }
//        $params['recycle'] = false;
//        return $this->mapper->getList($params, $isScope);
    }

    /**
     * 从回收站过去列表数据
     * @param array|null $params
     * @return array
     */
    public function getListByRecycle(?array $params = null): array
    {
        if ($params['select'] ?? null) {
            if (is_string($params['select'])){
                $params['select'] = explode(',', $params['select']);
            }
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
            $params['select'] = is_array($params['select']) ?$params['select']:explode(',', $params['select']);
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
            $params['select'] = is_array($params['select']) ?$params['select']:explode(',', $params['select']);
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
            $params['select'] = is_array($params['select']) ?$params['select']:explode(',', $params['select']);
        }
        $params['recycle'] = false;
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
            $params['select'] = is_array($params['select']) ?$params['select']:explode(',', $params['select']);
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

    public function downloadTemplate(){
        $this->mapper->downloadTemplate();
    }

    /**
     * 导出数据
     * @param array $params
     * @param string|null $dto
     * @param string|null $filename
     * @return NyuwaResponse
     */
    public function export(array $params, ?string $dto, string $filename = null):NyuwaResponse
    {
        if (empty($dto)) {
            return nyuwa_app(NyuwaResponse::class)->error('导出未指定DTO');
        }

        if (empty($filename)) {
            $filename = $this->mapper->getModel()->getTable();
        }

        return (new NyuwaCollection())->export($dto, $filename, $this->mapper->getList($params));
    }

    /**
     * 数据导入
     * @param string $dto
     * @param \Closure|null $closure
     * @return bool
     * @Transaction
     */
    public function import(string $dto, ?\Closure $closure = null): bool
    {
        return $this->mapper->import($dto, $closure);
    }

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


    //未加上用户数据权限判断，需要人为的判断可用性。

    /**
     * 从缓存读取一条数据
     * @param string $id
     * @return NyuwaModel|null
     */
    public function readByCache(string $id,$ttl = 3600): ?array
    {
        $cacheKey = "MODEL_".$this->mapper->getModel()->getTable()."_".$id;
        $value = Cache::get($cacheKey);
        if (!$value){
            $value = $this->mapper->read($id)->toArray();
            Cache::set($cacheKey,$value,$ttl);
            return $value;
        }
        return $value;
    }

    /**
     * 清理缓存
     * @param string $id
     * @return NyuwaModel|null
     */
    public function readByCacheClear(string $id): bool
    {
        $cacheKey = "MODEL_".$this->mapper->getModel()->getTable()."_".$id;
        return Cache::delete($cacheKey);
    }

    /**
     * 从缓存按条件读取一行数据
     * @param array $condition
     * @param array $column
     * @return mixed
     */
    public function firstByCache(array $condition, array $column = ['*'],$ttl = 3600): ?array
    {
        $keyMarkArr = array_merge($condition,['columns'=> $column]);
        ksort($keyMarkArr);
        $md5 = md5(json_encode($keyMarkArr,JSON_UNESCAPED_UNICODE));
        $cacheKey = "MODEL_".$this->mapper->getModel()->getTable()."_".$md5;
        $value = Cache::get($cacheKey);
        if (!$value){
            $value = $this->mapper->first($condition,$column)->toArray();
            Cache::set($cacheKey,$value,$ttl);
            return $value;
        }
        return $value;
    }
    /**
     * 清理缓存
     * @param string $id
     * @return NyuwaModel|null
     */
    public function firstByCacheClear(array $condition, array $column = ['*']): bool
    {
        $keyMarkArr = array_merge($condition,['columns'=> $column]);
        ksort($keyMarkArr);
        $md5 = md5(json_encode($keyMarkArr,JSON_UNESCAPED_UNICODE));
        $cacheKey = "MODEL_".$this->mapper->getModel()->getTable()."_".$md5;
        return Cache::delete($cacheKey);
    }


    /**
     * 从缓存获取列表数据，必须存在参数
     * @param array|null $params
     * @return array
     */
    public function getListByCache(?array $params = null,$ttl = 3600): array
    {
        if (empty($params)){
            $this->getList($params);
        }
        ksort($params);
        $md5 = md5(json_encode($params,JSON_UNESCAPED_UNICODE));
        $cacheKey = "LIST_".$this->mapper->getModel()->getTable()."_".$md5;
        $value = Cache::get($cacheKey);
        if (!$value){
            $value = $this->mapper->getList($params);
            Cache::set($cacheKey,$value,$ttl);
            return $value;
        }
        return $value;
    }

    /**
     * 清理缓存
     */
    public function getListByCacheClear(?array $params = null): bool
    {
        if (empty($params)){
            return true;
        }
        ksort($params);
        $md5 = md5(json_encode($params,JSON_UNESCAPED_UNICODE));
        $cacheKey = "LIST_".$this->mapper->getModel()->getTable()."_".$md5;
        return Cache::delete($cacheKey);
    }

    /**
     * 从缓存获取单个值
     * User:mike
     * @param array $condition
     * @param string $columns
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed|void|null
     */
    public function valueByCache(array $condition, string $columns = 'id',$ttl = 3600)
    {
        $keyMarkArr = array_merge($condition,['columns'=>$columns]);
        ksort($keyMarkArr);
        $md5 = md5(json_encode($keyMarkArr,JSON_UNESCAPED_UNICODE));
        $cacheKey = "VALUE_".$this->mapper->getModel()->getTable()."_".$md5;
        $value = Cache::get($cacheKey);
        if (!$value){
            $value = $this->mapper->value($condition, $columns);
            Cache::set($cacheKey,$value,$ttl);
            return $value;
        }
        return $value;
    }

    /**
     * 从缓存获取单列值
     * @param array $condition
     * @param string $columns
     * @param int $ttl
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed|void|null
     */
    public function pluckByCache(array $condition, string $columns = 'id',$ttl = 3600)
    {
        $keyMarkArr = array_merge($condition,['columns'=>$columns]);
        ksort($keyMarkArr);
        $md5 = md5(json_encode($keyMarkArr,JSON_UNESCAPED_UNICODE));
        $cacheKey = "PLUCK_".$this->mapper->getModel()->getTable()."_".$md5;
        $value = Cache::get($cacheKey);
        if (!$value){
            $value = $this->mapper->pluck($condition, $columns);
            Cache::set($cacheKey,$value,$ttl);
            return $value;
        }
        return $value;
    }

}
