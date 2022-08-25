<?php

declare(strict_types=1);

namespace app\admin\service\setting;


use app\admin\mapper\setting\SettingGenerateColumnsMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SettingGenerateColumnsService extends AbstractService
{
    /**
     * @Inject
     * @var SettingGenerateColumnsMapper
     */
    public $mapper;

    /**
     * 循环插入数据
     * @param array $data
     * @return string
     */
    public function save(array $data): string
    {
        $default_column = ['created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'remark'];
        // 组装数据
        foreach ($data as $k => $item) {

            $column = [
                'table_id' => $item['table_id'],
                'column_name' => $item['column_name'],
                'column_comment' => $item['column_comment'],
                'column_type' => $item['data_type'],
                'is_pk' => empty($item['column_key']) ? '0' : '1' ,
                'query_type' => 'eq',
                'view_type' => 'text',
                'sort' => count($data) - $k,
                'allow_roles' => $item['allow_roles']??null,
                'options' => $item['options']??null
            ];

            // 设置默认选项
            if (!in_array($item['column_name'], $default_column) && empty($item['column_key'])) {
                $column = array_merge(
                    $column,
                    ['is_insert' => 1, 'is_edit' => '1', 'is_list' => '1', 'is_query' => '1']
                );
            }
            //做一些智能的优化，索引添加。

            $this->mapper->save($column);
        }
        return "1";
    }

}
