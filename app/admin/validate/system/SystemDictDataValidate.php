<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemDictDataValidate extends NyuwaValidate
{

    protected $rule = [
        "code" => "required|max:100",  //字典标示
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "deleted_at" => "required|date",  //删除时间
        "id" => "required|numeric",  //主键
        "label" => "required|max:50",  //字典标签
        "remark" => "required|max:255",  //备注
        "sort" => "required|numeric",  //排序
        "status" => "required|max:1",  //状态 (0正常 1停用)
        "type_id" => "required|numeric",  //字典类型ID
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者
        "value" => "required|max:100",  //字典值
    ];

    protected $customAttributes = [
        "code" => "字段 字典标示:code",
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "deleted_at" => "字段 删除时间:deleted_at",
        "id" => "字段 主键:ID",
        "label" => "字段 字典标签:label",
        "remark" => "字段 备注:remark",
        "sort" => "字段 排序:sort",
        "status" => "字段 状态 (0正常 1停用):status",
        "type_id" => "字段 字典类型ID:type_id",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
        "value" => "字段 字典值:value",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["label","code","value"],
        "update" => ["id","label","code","value"],
        "status" => ["id","status"],
    ];

}
