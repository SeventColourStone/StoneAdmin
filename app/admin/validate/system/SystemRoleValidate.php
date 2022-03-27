<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemRoleValidate extends NyuwaValidate
{

    protected $rule = [
        "code" => "required|min:3|max:100",  //角色代码
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "data_scope" => "required|string",  //数据范围（0：全部数据权限 1：自定义数据权限 2：本部门数据权限 3：本部门及以下数据权限 4：本人数据权限）
        "deleted_at" => "required|date",  //删除时间
        "id" => "required|numeric",  //主键，角色ID
        "name" => "required|max:30",  //角色名称
        "remark" => "string",  //备注
        "sort" => "required|numeric",  //排序
        "status" => "required|string",  //状态 (0正常 1停用)
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者
    ];

    protected $customAttributes = [
        "code" => "字段 角色代码:code",
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "data_scope" => "字段 数据范围:data_scope",
        "deleted_at" => "字段 删除时间:deleted_at",
        "id" => "字段 主键，角色ID:id",
        "name" => "字段 角色名称:name",
        "remark" => "字段 备注:remark",
        "sort" => "字段 排序:sort",
        "status" => "字段 状态 (0正常 1停用):status",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["name","code","dept_id","role_ids"],
        "update" => ["id","name","code","dept_id","role_ids"],
        "menuPermission" => ["id","dept_id","role_ids"],
        "status" => ["id","status"],
    ];

}
