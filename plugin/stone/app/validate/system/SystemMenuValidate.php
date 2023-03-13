<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\system;


use plugin\stone\nyuwa\NyuwaValidate;

class SystemMenuValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "parent_id" => "required",  //父ID
        "level" => "required|max:500",  //组级集合
        "name" => "required|max:30",  //菜单名称
        "code" => "required|min:3|max:50",  //菜单标识代码
        "icon" => "required|max:50",  //菜单图标
        "route" => "required|max:200",  //路由地址
        "component" => "required|max:255",  //组件路径
        "redirect" => "required|max:255",  //跳转地址
        "is_hidden" => "required|string",  //是否隐藏 (0是 1否)
        "type" => "required|string",  //菜单类型, (M菜单 B按钮 L链接 I iframe)
        "open_type" => "required|max:10",  //当 type 为 1 时，openType 生效，_iframe 正常打开 _blank 新建浏览器标签页
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
        "name" => "字段 菜单名称:name",
        "code" => "字段 菜单标识代码:code",
        "icon" => "字段 菜单图标:icon",
        "route" => "字段 路由地址:route",
        "component" => "字段 组件路径:component",
        "redirect" => "字段 跳转地址:redirect",
        "is_hidden" => "字段 是否隐藏 (0是 1否):is_hidden",
        "type" => "字段 菜单类型, (M菜单 B按钮 L链接 I iframe):type",
        "open_type" => "字段 当 type 为 1 时，openType 生效，_iframe 正常打开 _blank 新建浏览器标签页:open_type",
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
        "create" => ['parent_id', 'level', 'name', 'code', 'icon', 'route', 'component', 'redirect', 'is_hidden', 'type', 'open_type', 'status', 'sort',],
        "update" => ["id"],
        "status" => ["id","status"],
        "menuCreate" => ["name","code"],
    ];
}
