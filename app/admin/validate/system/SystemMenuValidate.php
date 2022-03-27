<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemMenuValidate extends NyuwaValidate
{

    protected $rule = [
        "code" => "required|min:3|max:100",  //菜单标识代码
        "component" => "required|max:255",  //组件路径
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "deleted_at" => "required|date",  //删除时间
        "icon" => "required|max:50",  //菜单图标
        "id" => "required|numeric",  //主键
        "is_hidden" => "required|max:1",  //是否隐藏 (0是 1否)
        "level" => "required|max:500",  //组级集合
        "name" => "required|max:50",  //菜单名称
        "parent_id" => "required|numeric",  //父ID
        "redirect" => "required|max:255",  //跳转地址
        "remark" => "required|max:255",  //备注
        "route" => "required|max:200",  //路由地址
        "sort" => "required|numeric",  //排序
        "status" => "required|max:1",  //状态 (0正常 1停用)
        "type" => "required|max:1",  //菜单类型, (M菜单 B按钮 L链接 I iframe)
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者
    ];

    protected $customAttributes = [
        "code" => "字段 菜单标识代码:code",
        "component" => "字段 组件路径:component",
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "deleted_at" => "字段 删除时间:deleted_at",
        "icon" => "字段 菜单图标:icon",
        "id" => "字段 主键:id",
        "is_hidden" => "字段 是否隐藏 (0是 1否):is_hidden",
        "level" => "字段 组级集合:level",
        "name" => "字段 菜单名称:name",
        "parent_id" => "字段 父ID:parent_id",
        "redirect" => "字段 跳转地址:redirect",
        "remark" => "字段 备注:remark",
        "route" => "字段 路由地址:route",
        "sort" => "字段 排序:sort",
        "status" => "字段 状态 (0正常 1停用):status",
        "type" => "字段 菜单类型, (M菜单 B按钮 L链接 I iframe):type",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["name","code"],
        "update" => ["id","name","code"],
    ];

}
