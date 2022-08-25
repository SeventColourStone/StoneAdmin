<?php
/**

 */

namespace nyuwa\traits;

use app\admin\model\system\SystemDictData;
use app\admin\model\system\SystemDictField;
use app\admin\model\system\SystemDictType;
use app\admin\service\system\SystemDictFieldService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\HigherOrderTapProxy;
use nyuwa\NyuwaCollection;
use nyuwa\NyuwaModel;
use support\Db;
use support\Model;
use function DI\string;

trait MapperTrait
{
    /**
     * @var NyuwaModel
     */
    public $model;

    /**
     * @var integer
     */
    public $userId = 0;

    public function setUserId($userId){
        $this->userId = $userId;
    }

    /**
     * 获取列表数据
     * @param array|null $params
     * @param bool $isScope
     * @return array
     */
    public function getList(?array $params, bool $isScope = true): array
    {
        return $this->listQuerySetting($params, $isScope)->get()->toArray();
    }

    /**
     * 获取列表数据（带分页）
     * @param array|null $params
     * @param bool $isScope
     * @param string $pageName
     * @return array
     */

    public function  getPageList(?array $params, bool $isScope = true, string $pageName = 'page'): array
    {
        $paginate = $this->listQuerySetting($params, $isScope)->paginate(
            $params['pageSize'] ?? $this->model::PAGE_SIZE, ['*'], $pageName, $params[$pageName] ?? 1
        );
        return $this->setPaginate($paginate);
    }

    /**
     * 设置数据库分页
     * @param  $paginate
     * @return array
     */
    public function setPaginate(LengthAwarePaginator $paginate): array
    {
//        $this->handleTransform(Collection::make($paginate->items()));
        return [
            'items' => $paginate->items(),
            'pageInfo' => [
                'total' => $paginate->total(),
                'currentPage' => $paginate->currentPage(),
                'totalPage' => $paginate->lastPage()
            ]
        ];
    }

    /**
     * 获取树列表
     * @param array|null $params
     * @param bool $isScope
     * @param string $id
     * @param string $parentField
     * @param string $children
     * @return array
     */
    public function getTreeList(
        ?array $params = null,
        bool $isScope = true,
        string $id = 'id',
        string $parentField = 'parent_id',
        string $children='children'
    ): array
    {
        $params['_mainAdmin_tree'] = true;
        $params['_mainAdmin_tree_pid'] = $parentField;
        $data = $this->listQuerySetting($params, $isScope)->get();
//        var_dump($params);
        //转换
//        $this->handleTransform($data);
        return $data->toTree([], $data[0]->{$parentField} ?? 0, $id, $parentField, $children);
    }

    /**
     * 返回模型查询构造器
     * @param array|null $params
     * @param bool $isScope 对数据进行宏验证
     * @return Builder
     */
    public function listQuerySetting(?array $params, bool $isScope): Builder
    {
        $query = (($params['recycle'] ?? false) === true) ? $this->model::onlyTrashed() : $this->model::query();

        if ($params['select'] ?? false) {
            $query->select($this->filterQueryAttributes($params['select']));
        }

        if ($params['in']?? false){
            foreach ($params['in'] as $key => $vals){
                if (is_numeric($key)){
                    foreach ($vals as $k => $newVals){
                        $query->whereIn($k,$newVals);
                    }
                }else{
                    $query->whereIn($key,$vals);
                }
            }
        }

        if ($params['distinct'] ?? false){
            $query->distinct();
        }

        if ($params['groupBy']?? false){
            $query->groupBy($params['groupBy']);
        }

        $query = $this->handleOrder($query, $params);
//暂停用户数据权限
        $isScope && $query->userDataScope(1);//$this->userId > 0?$this->userId:null
//        var_dump($query->toSql());
        return $this->handleSearch($query, $params);
    }

    /**
     * 字段字典转换处理器
     */
    public function handleTransform($collection = null){
        //根据数据转换
        $table = $this->getModel()->getTable();
        $systemDictFieldService = nyuwa_app(SystemDictFieldService::class);
        if (is_array($collection)){
            $collection = NyuwaCollection::make($collection);
        }
        //查找是否存在字段字典绑定
        $fieldArr = $systemDictFieldService->getTableField($table);
        $collection->transform(function ($item,$key) use ($table, $systemDictFieldService, $fieldArr) {
            $arr = $item->toArray();//一个表的数据
            //查找得到转换
            foreach ($arr as $key => $v){
                if (in_array($key,$fieldArr)){
                    //转换字典数据
                    $item[$key."_label"] = $systemDictFieldService->parseDictLabel($table,$key,$v);
                }
            }
            return $item;
        });
    }

    /**
     * 排序处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleOrder(Builder $query, array &$params): Builder
    {
        // 对树型数据强行加个排序
        if (isset($params['_mainAdmin_tree'])) {
            $query->orderBy($params['_mainAdmin_tree_pid']);
        }

        if ($params['orderBy'] ?? false) {
            if (is_array($params['orderBy'])) {
                foreach ($params['orderBy'] as $key => $order) {
                    $query->orderBy($order, $params['orderType'][$key] ?? 'asc');
                }
            } else {
                $query->orderBy($params['orderBy'], $params['orderType'] ?? 'asc');
            }
        }

        return $query;
    }

    /**
     * 搜索处理器，默认全等字符串查找
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        //参数里面的基本字段，默认搜索前先剔除业务字段
        $keys = ['select','_mainAdmin_tree','_mainAdmin_tree_pid',
            'orderBy','orderType','groupBy','distinct','recycle','is_hidd',"_","page","pageSize"];
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($params as $name => $val) {
            if (in_array($name, $keys)) {
                unset($params[$name]);
            }
            if (!in_array(trim($name), $attrs)) {
                unset($params[$name]);
            }
        }
        foreach ($params as $name => $val){
            $query->where($name,trim($val));
        }

        return $query;
    }

    /**
     * 过滤条件字段不存在的属性
     * @param array $fields
     * @param bool $removePk
     * @return array
     */
    protected function filterWhereAttributes(array $fields): array
    {
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($fields as $key => $field) {
            if (!in_array(trim($field), $attrs)) {
                $fields[$key] = trim($field);
            } else {
                $fields[$key] = trim($field);
            }
        }
        return ( count($fields) < 1 ) ? ['*'] : $fields;
    }

    /**
     * 过滤查询字段不存在的属性，如果是是as重定义则保留
     * @param array $fields
     * @param bool $removePk
     * @return array
     */
    protected function filterQueryAttributes(array $fields, bool $removePk = false): array
    {
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($fields as $key => $field) {
            if (!in_array(trim($field), $attrs)) {
                if (strpos(mb_strtolower(trim($field))," as ") !== false){
                    $fields[$key] = trim($field);
                }else
                    unset($fields[$key]);
            } else {
                $fields[$key] = trim($field);
            }
        }
        if ($removePk && in_array($model->getKeyName(), $fields)) {
            unset($fields[array_search($model->getKeyName(), $fields)]);
        }
        $model = null;
        var_dump($fields);
        return ( count($fields) < 1 ) ? ['*'] : $fields;
    }

    /**
     * 过滤新增或写入不存在的字段
     * @param array $data
     * @param bool $removePk
     */
    protected function filterExecuteAttributes(array &$data, bool $removePk = false): void
    {
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($data as $name => $val) {
            if (!in_array($name, $attrs)) {
                unset($data[$name]);
            }
        }
        if ($removePk && isset($data[$model->getKeyName()])) {
            unset($data[$model->getKeyName()]);
        }
        $model = null;
    }

    /**
     * 新增数据
     * @param array $data
     * @return string
     */
    public function save(array $data): string
    {
        $this->filterExecuteAttributes($data);
        $model = $this->model::query()->create($data);
        return $model->{$model->getKeyName()};
    }

//    public function saveOrUpdate(array $data):string
//    {
//        $this->filterExecuteAttributes($data);
//        $model = $this->model::query()->updateOrCreate($data);
//        return $model->{$model->getKeyName()};
//    }

    /**
     * 读取一条数据
     * @param string $id
     * @return NyuwaModel
     */
    public function read(string $id): ?NyuwaModel
    {
        return ($this->model::query()->find($id)) ?? null;
    }

    /**
     * 按条件读取一行数据
     * @param array $condition
     * @param array $column
     * @return mixed
     */
    public function first(array $condition, array $column = ['*']): ?NyuwaModel
    {
        return ($model = $this->model::query()->where($condition)->first($column)) ? $model : null;
    }

    /**
     * 获取单个值
     * @param array $condition
     * @param string $columns
     * @return HigherOrderTapProxy|mixed|void|null
     */
    public function value(array $condition, string $columns = 'id')
    {
        return ($model = $this->model::where($condition)->value($columns)) ? $model : null;
    }

    /**
     * 获取单列值
     * @param array $condition
     * @param string $columns
     * @return array|null
     */
    public function pluck(array $condition, string $columns = 'id'): array
    {
        return $this->model::where($condition)->pluck($columns)->toArray();
    }

    /**
     * 从回收站读取一条数据
     * @param string $id
     * @return NyuwaModel
     * @noinspection PhpUnused
     */
    public function readByRecycle(string $id): ?NyuwaModel
    {
        return ($model = $this->model::withTrashed()->find($id)) ? $model : null;
    }

    /**
     * 单个或批量软删除数据
     * @param array $ids
     * @return bool
     */
    public function delete(array $ids): bool
    {
        $this->model::destroy($ids);
        return true;
    }

    /**
     * 更新一条数据
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        $this->filterExecuteAttributes($data, true);
        $model = $this->model::find($id);
        //按id更新如果data存在id则移除id
        foreach ($data as $name => $val) {
            $model[$name] = $val;
        }
        return $model->save();
    }

    /**
     * 按条件更新数据
     * @param array $condition
     * @param array $data
     * @return bool
     */
    public function updateByCondition(array $condition, array $data): bool
    {
//        if (isset($data['id'])) unset($data['id']);
        $this->filterExecuteAttributes($data, true);
        return $this->model::query()->where($condition)->update($data) > 0;
    }

    /**
     * 单个或批量真实删除数据
     * @param array $ids
     * @return bool
     */
    public function realDelete(array $ids): bool
    {
        foreach ($ids as $id) {
            $model = $this->model::withTrashed()->find($id);
            $model && $model->forceDelete();
        }
        return true;
    }

    /**
     * 单个或批量从回收站恢复数据
     * @param array $ids
     * @return bool
     */
    public function recovery(array $ids): bool
    {
        $this->model::withTrashed()->whereIn((new $this->model)->getKeyName(), $ids)->restore();
        return true;
    }

    /**
     * 单个或批量禁用数据
     * @param array $ids
     * @param string $field
     * @return bool
     */
    public function disable(array $ids, string $field = 'status'): bool
    {
        $this->model::query()->whereIn((new $this->model)->getKeyName(), $ids)->update([$field => $this->model::DISABLE]);
        return true;
    }

    /**
     * 单个或批量启用数据
     * @param array $ids
     * @param string $field
     * @return bool
     */
    public function enable(array $ids, string $field = 'status'): bool
    {
        $this->model::query()->whereIn((new $this->model)->getKeyName(), $ids)->update([$field => $this->model::ENABLE]);
        return true;
    }

    /**
     * @return NyuwaModel
     */
    public function getModel(): NyuwaModel
    {
        return new $this->model;
    }


    /**
     * 根据表注释生成模板文件。
     */
    public function downloadTemplate(){
        $sql = <<<SQL
SELECT COLUMN_NAME,column_comment,COLUMN_KEY,ORDINAL_POSITION
FROM information_schema.COLUMNS
WHERE table_name = :table order by `ORDINAL_POSITION` ;
SQL;
        $table = $this->getModel()->getTable();

        $info = Db::select($sql,['table'=>$table]);
        //create file
        $list = [];
        foreach ($info as $item){
            $is_pk = 1;
            if ($item->COLUMN_KEY == "PRI"){
                $is_pk = 0;
                //跳过主键
                continue;
            }
            //跳过其他字段
//            $genColumns = [
//                "column_name" => $item->COLUMN_NAME,  //字段名称
//                "column_comment" => $item->COLUMN_COMMENT,  //字段注释
//            ];
            $list []= $item->COLUMN_COMMENT;
        }
        $config = [
            'path' => '/home/viest' // xlsx文件保存路径
        ];
        $excel  = new \Vtiful\Kernel\Excel($config);

        // fileName 会自动创建一个工作表，你可以自定义该工作表名称，工作表名称为可选参数
        $filePath = $excel->fileName("{$table}_temple.xlsx", 'sheet1')
            ->header($list)
            ->data([
//                ['Rent', 1000],
//                ['Gas',  100],
//                ['Food', 300],
//                ['Gym',  50],
            ])
            ->output();
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
        //读取表结构并使用字段
        $table = $this->getModel()->getTable();

        return (new NyuwaCollection())->import($dto, $this->getModel(), $closure);
    }

    /**
     * 闭包通用查询设置
     * @param \Closure|null $closure 传入的闭包查询
     * @return Builder
     */
    public function settingClosure(?\Closure $closure = null): Builder
    {
        return $this->model::where(function($query) use($closure) {
            if ($closure instanceof \Closure) {
                $closure($query);
            }
        });
    }

    /**
     * 闭包通用方式查询一条数据
     * @param \Closure|null $closure
     * @param array|string[] $column
     * @return Builder|Model|null
     */
    public function one(?\Closure $closure = null, array $column = ['*'])
    {
        return $this->settingClosure($closure)->select($column)->first();
    }

    /**
     * 闭包通用方式查询数据集合
     * @param \Closure|null $closure
     * @param array|string[] $column
     * @return array
     */
    public function get(?\Closure $closure = null, array $column = ['*']): array
    {
        return $this->settingClosure($closure)->get($column)->toArray();
    }

    /**
     * 闭包通用方式统计
     * @param \Closure|null $closure
     * @param string $column
     * @return int
     */
    public function count(?\Closure $closure = null, string $column = '*'): int
    {
        return $this->settingClosure($closure)->count($column);
    }

    /**
     * 闭包通用方式查询最大值
     * @param \Closure|null $closure
     * @param string $column
     * @return mixed|string|void
     */
    public function max(?\Closure $closure = null, string $column = '*')
    {
        return $this->settingClosure($closure)->max($column);
    }

    /**
     * 闭包通用方式查询最小值
     * @param \Closure|null $closure
     * @param string $column
     * @return mixed|string|void
     */
    public function min(?\Closure $closure = null, string $column = '*')
    {
        return $this->settingClosure($closure)->min($column);
    }
}
