<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemDictTypeValidate extends NyuwaValidate
{

    protected $rule = [
        "code" => "required|max:100",  //字典标示
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "deleted_at" => "required|date",  //删除时间
        "id" => "required|numeric",  //主键
        "name" => "required|max:50",  //字典名称
        "remark" => "required|max:255",  //备注
        "status" => "required|max:1",  //状态 (0正常 1停用)
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者
    ];

    protected $customAttributes = [
        "code" => "字段 字典标示:code",
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "deleted_at" => "字段 删除时间:deleted_at",
        "id" => "字段 主键:id",
        "name" => "字段 字典名称:name",
        "remark" => "字段 备注:remark",
        "status" => "字段 状态 (0正常 1停用):status",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["name","code"],
        "update" => ["id","name","code"],
    ];

}
