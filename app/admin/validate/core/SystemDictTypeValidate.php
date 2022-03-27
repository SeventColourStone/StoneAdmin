<?php

declare(strict_types=1);


namespace app\admin\validate\core;


use nyuwa\NyuwaValidate;

class SystemDictTypeValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "name" => "required|max:50",  //字典名称
        "code" => "required|max:100",  //字典标示
        "status" => "required|string",  //状态 (0正常 1停用)
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "name" => "字段 字典名称:name",
        "code" => "字段 字典标示:code",
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
        "create" => ['name', 'code', 'status',],
        "update" => ["id"],
        "status" => ["id","status"],
    ];
}
