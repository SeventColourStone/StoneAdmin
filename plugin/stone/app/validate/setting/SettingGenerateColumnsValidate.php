<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\setting;


use plugin\stone\nyuwa\NyuwaValidate;

class SettingGenerateColumnsValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "table_id" => "required",  //所属表ID
        "column_name" => "required|max:200",  //字段名称
        "column_comment" => "required|max:255",  //字段注释
        "column_type" => "required|max:50",  //字段类型
        "is_pk" => "required|string",  //0 非主键 1 主键
        "is_required" => "required|string",  //0 非必填 1 必填
        "is_insert" => "required|string",  //0 非插入字段 1 插入字段
        "is_edit" => "required|string",  //0 非编辑字段 1 编辑字段
        "is_list" => "required|string",  //0 非列表显示字段 1 列表显示字段
        "is_query" => "required|string",  //0 非查询字段 1 查询字段
        "query_type" => "required|max:100",  //查询方式 eq 等于, neq 不等于, gt 大于, lt 小于, like 范围
        "view_type" => "required|max:100",  //页面控件，text, textarea, password, select, checkbox, radio, date, upload, ma-upload（封装的上传控件）
        "dict_type" => "required|max:200",  //字典类型
        "allow_roles" => "required|max:255",  //允许查看该字段的角色
        "options" => "required|max:1000",  //字段其他设置
        "sort" => "required|numeric",  //排序
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "table_id" => "字段 所属表ID:table_id",
        "column_name" => "字段 字段名称:column_name",
        "column_comment" => "字段 字段注释:column_comment",
        "column_type" => "字段 字段类型:column_type",
        "is_pk" => "字段 0 非主键 1 主键:is_pk",
        "is_required" => "字段 0 非必填 1 必填:is_required",
        "is_insert" => "字段 0 非插入字段 1 插入字段:is_insert",
        "is_edit" => "字段 0 非编辑字段 1 编辑字段:is_edit",
        "is_list" => "字段 0 非列表显示字段 1 列表显示字段:is_list",
        "is_query" => "字段 0 非查询字段 1 查询字段:is_query",
        "query_type" => "字段 查询方式 eq 等于, neq 不等于, gt 大于, lt 小于, like 范围:query_type",
        "view_type" => "字段 页面控件，text, textarea, password, select, checkbox, radio, date, upload, ma-upload（封装的上传控件）:view_type",
        "dict_type" => "字段 字典类型:dict_type",
        "allow_roles" => "字段 允许查看该字段的角色:allow_roles",
        "options" => "字段 字段其他设置:options",
        "sort" => "字段 排序:sort",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['table_id', 'column_name', 'column_comment', 'column_type', 'is_pk', 'is_required', 'is_insert', 'is_edit', 'is_list', 'is_query', 'query_type', 'view_type', 'dict_type', 'allow_roles', 'options', 'sort',],
        "update" => ["id"],
    ];
}
