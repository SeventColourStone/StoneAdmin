<?php

declare(strict_types=1);


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemDictDataValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "type_id" => "required",  //字典类型ID
        "label" => "required|max:50",  //字典名称
        "value" => "required|max:100",  //字典值
        "code" => "required|max:100",  //字典编码
        "sort" => "required|numeric",  //排序
        "status" => "required|string",  //状态
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "type_id" => "字段 字典类型ID:type_id",
        "label" => "字段 字典名称:label",
        "value" => "字段 字典值:value",
        "code" => "字段 字典编码:code",
        "sort" => "字段 排序:sort",
        "status" => "字段 状态:status",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['type_id', 'label', 'value', 'code', 'sort', 'status',],
        "update" => ["id","label","code","value"],
        "status" => ["id","status"],
    ];
}
