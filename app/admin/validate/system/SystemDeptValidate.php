<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemDeptValidate extends NyuwaValidate
{

    protected $rule = [
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "deleted_at" => "required|date",  //删除时间
        "id" => "required|numeric",  //主键
        "leader" => "required|max:20",  //负责人
        "level" => "required|max:500",  //组级集合
        "name" => "required|max:30",  //部门名称
        "parent_id" => "required|numeric",  //父ID
        "phone" => "required|max:11",  //联系电话
        "remark" => "required|max:255",  //备注
        "sort" => "required|numeric",  //排序
        "status" => "required|string",  //状态 (0正常 1停用)
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者
    ];

    protected $customAttributes = [
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "deleted_at" => "字段 删除时间:deleted_at",
        "id" => "字段 主键:ID",
        "leader" => "字段 负责人:leader",
        "level" => "字段 组级集合:level",
        "name" => "字段 部门名称:name",
        "parent_id" => "字段 父ID:parent_id",
        "phone" => "字段 联系电话:phone",
        "remark" => "字段 备注:remark",
        "sort" => "字段 排序:sort",
        "status" => "字段 状态 (0正常 1停用):status",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["name"],
        "update" => ["id","name"],
        "status" => ["id","status"],
    ];

}
