<?php

declare(strict_types=1);


namespace app\admin\validate\test;


use nyuwa\NyuwaValidate;

class TestInfo1Validate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //id
        "ceshi_name" => "required|max:20",  //
        "status" => "required|string",  //状态 (0正常 1停用)
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 id:id",
        "ceshi_name" => "字段 :ceshi_name",
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
        "create" => ['ceshi_name',],
        "update" => ["id"],
        "status" => ["id","status"],
    ];
}
