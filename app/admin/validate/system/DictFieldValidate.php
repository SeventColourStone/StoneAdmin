<?php


namespace app\admin\validate\system;

use nyuwa\NyuwaValidate;

class DictFieldValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required|numeric",  //
        "dict_type_id" => "required|numeric",  //system_dict_type 关联id
        "table_name" => "required|max:80",  //表名
        "field_name" => "required|max:60",  //字段名
        "default_val" => "required|max:80",  //兜底值
        "status" => "required|string",  //状态 (0正常 1停用)
        "created_by" => "required|numeric",  //创建者
        "updated_by" => "required|numeric",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 :id",
        "dict_type_id" => "字段 system_dict_type 关联id:dict_type_id",
        "table_name" => "字段 表名:table_name",
        "field_name" => "字段 字段名:field_name",
        "default_val" => "字段 兜底值:default_val",
        "status" => "字段 状态 (0正常 1停用):status",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["dict_type_id","table_name","field_name","default_val"],
        "update" => ["id","dict_type_id","table_name","field_name","default_val"],
        "status" => ["id","status"],
    ];
}
