<?php


namespace app\admin\service\setting;


use DI\Annotation\Inject;
use Illuminate\Support\Collection;
use nyuwa\abstracts\AbstractService;
use nyuwa\helper\Str;
use support\Db;

class DataSourceService extends AbstractService
{
    /**
     * 获取表状态分页列表
     * @param array|null $params
     * @return array
     */
    public function getPageList(?array $params = []): array
    {
        return $this->getArrayToPageList($params);
    }

    /**
     * 数组数据搜索器
     * @param Collection $collect
     * @param array $params
     * @return Collection
     */
    protected function handleArraySearch(Collection $collect, array $params)
    {
        if ($params['name'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row->Name, $params['name']);
            });
        }
        if ($params['engine'] ?? false) {
            $collect = $collect->where('Engine', $params['engine']);
        }
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
        $tables = [];
        foreach ($data as $item) {
            $tables[] = array_change_key_case((array)$item);
        }
        return $tables;
    }

    /**
     * 设置需要分页的数组数据
     * @param array $params
     * @return array
     */
    protected function getArrayData(array $params = []): array
    {
        return Db::select("SHOW TABLE STATUS WHERE name != 'migrations'");
    }

    /**
     * 获取表字段
     * @param string $table
     * @return array
     */
    public function getColumnList(string $table): array
    {
        if ($table) {
            $result = Db::select("select `column_key` as `column_key`, `column_name` as `column_name`, `data_type` as `data_type`, `column_comment` as `column_comment`, `extra` as `extra`, `column_type` as `column_type` from information_schema.columns where TABLE_NAME=:table order by ORDINAL_POSITION",['table'=>$table]);
            return json_decode(json_encode($result,JSON_UNESCAPED_UNICODE),true);
        } else {
            return [];
        }
    }

    /**
     * 优化表
     * @param array $tables
     * @return bool
     */
    public function optimize(array $tables): bool
    {
        foreach ($tables as $table) {
            Db::select('optimize table `?`', [$table]);
        }
        return true;
    }

    /**
     * 清理表碎片
     * @param array $tables
     * @return bool
     */
    public function fragment(array $tables): bool
    {
        foreach ($tables as $table) {
            Db::select('analyze table `?`', [$table]);
        }
        return true;
    }
}
