<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\system;


use plugin\stone\nyuwa\NyuwaValidate;

class SystemDictFieldValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //id
        "dict_type_id" => "required",  //字典类型id
        "table_name" => "required|max:80",  //表名
        "field_name" => "required|max:60",  //字段名
        "default_val" => "required|max:80",  //默认值
        "status" => "required|string",  //状态
        "view_type" => "required|max:40",  //表单类型
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 id:id",
        "dict_type_id" => "字段 字典类型id:dict_type_id",
        "table_name" => "字段 表名:table_name",
        "field_name" => "字段 字段名:field_name",
        "default_val" => "字段 默认值:default_val",
        "status" => "字段 状态:status",
        "view_type" => "字段 表单类型:view_type",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['dict_type_id', 'table_name', 'field_name', 'default_val', 'status', 'view_type',],
        "update" => ["id"],
    ];
}
