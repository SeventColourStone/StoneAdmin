<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemPostValidate extends NyuwaValidate
{

    protected $rule = [
        "code" => "required|min:3|max:100",  //岗位代码
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "deleted_at" => "required|date",  //删除时间
        "id" => "required|numeric",  //主键
        "name" => "required|max:50",  //岗位名称
        "remark" => "required|max:255",  //备注
        "sort" => "required|numeric",  //排序
        "status" => "required|max:1",  //状态 (0正常 1停用)
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者
    ];

    protected $customAttributes = [
        "code" => "字段 岗位代码:code",
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "deleted_at" => "字段 删除时间:deleted_at",
        "id" => "字段 主键:id",
        "name" => "字段 岗位名称:name",
        "remark" => "字段 备注:remark",
        "sort" => "字段 排序:sort",
        "status" => "字段 状态 (0正常 1停用):status",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["name","code"],
        "update" => ["id","name","code"],
        "status" => ["id","status"],
    ];
}
