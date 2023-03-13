<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\system;



use plugin\stone\nyuwa\NyuwaValidate;

class SystemDeptValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "parent_id" => "required",  //父ID
        "level" => "required|max:500",  //组级集合
        "name" => "required|max:30",  //部门名称
        "leader" => "required|max:20",  //负责人
        "phone" => "required|max:11",  //联系电话
        "status" => "required|string",  //状态 (0正常 1停用)
        "sort" => "required|numeric",  //排序
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "parent_id" => "字段 父ID:parent_id",
        "level" => "字段 组级集合:level",
        "name" => "字段 部门名称:name",
        "leader" => "字段 负责人:leader",
        "phone" => "字段 联系电话:phone",
        "status" => "字段 状态 (0正常 1停用):status",
        "sort" => "字段 排序:sort",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['parent_id',  'name', 'leader', 'status', 'sort',],
        "update" => ["id"],
        "status" => ["id","status"],
    ];
}
