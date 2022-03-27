<?php

declare(strict_types=1);


namespace app\admin\validate\generate;


use nyuwa\NyuwaValidate;

class GenerateColumnsValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "table_id" => "required",  //所属表ID
        "column_name" => "required|max:200",  //字段名称
        "column_comment" => "required|max:255",  //字段注释
        "data_type" => "required|max:50",  //字段类型
        "date_max_len" => "required",  //字段长度
        "is_pk" => "required|string",  //是否主键
        "is_required" => "required|string",  //是否必填
        "is_insert" => "required|string",  //是否表单插入
        "is_edit" => "required|string",  //是否编辑
        "is_list" => "required|string",  //是否列表显示
        "is_query" => "required|string",  //是否查询字段
        "query_type" => "required|max:100",  //查询方式
        "view_type" => "required|max:100",  //页面控件
        "dict_type" => "required|max:200",  //字典类型
        "sort" => "required|numeric",  //排序
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "table_id" => "字段 所属表ID:table_id",
        "column_name" => "字段 字段名称:column_name",
        "column_comment" => "字段 字段注释:column_comment",
        "data_type" => "字段 字段类型:data_type",
        "date_max_len" => "字段 字段长度:date_max_len",
        "is_pk" => "字段 是否主键:is_pk",
        "is_required" => "字段 是否必填:is_required",
        "is_insert" => "字段 是否表单插入:is_insert",
        "is_edit" => "字段 是否编辑:is_edit",
        "is_list" => "字段 是否列表显示:is_list",
        "is_query" => "字段 是否查询字段:is_query",
        "query_type" => "字段 查询方式:query_type",
        "view_type" => "字段 页面控件:view_type",
        "dict_type" => "字段 字典类型:dict_type",
        "sort" => "字段 排序:sort",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['table_id', 'column_name', 'column_comment', 'data_type', 'date_max_len', 'is_pk', 'is_required', 'is_insert', 'is_edit', 'is_list', 'is_query', 'query_type', 'view_type', 'dict_type', 'sort', 'remark',],
        "update" => ["id"],
        "status" => ["id","status"],
    ];
}
