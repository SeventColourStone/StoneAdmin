<?php

declare(strict_types=1);


namespace app\admin\validate\setting;


use nyuwa\NyuwaValidate;

class SettingGenerateTablesValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "table_name" => "required|max:200",  //表名称
        "table_comment" => "required|max:500",  //表注释
        "module_name" => "required|max:100",  //所属模块
        "namespace" => "required|max:255",  //命名空间
        "menu_name" => "required|max:100",  //生成菜单名
        "belong_menu_id" => "",  //所属菜单
        "package_name" => "",  //控制器包名
        "type" => "required|max:100",  //生成类型，single 单表CRUD，tree 树表CRUD，parent_sub父子表CRUD
        "generate_type" => "required|string",  //0 压缩包下载 1 生成到模块
        "generate_menus" => "required|array",  //生成菜单列表
        "build_menu" => "required|string",  //是否构建菜单
        "component_type" => "required|string",  //组件显示方式
        "options" => "",  //其他业务选项
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "remark" => "",  //备注

        'columns' => 'required|array',

        'names' => 'required|array',
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "table_name" => "字段 表名称:table_name",
        "table_comment" => "字段 表注释:table_comment",
        "module_name" => "字段 所属模块:module_name",
        "namespace" => "字段 命名空间:namespace",
        "menu_name" => "字段 生成菜单名:menu_name",
        "belong_menu_id" => "字段 所属菜单:belong_menu_id",
        "package_name" => "字段 控制器包名:package_name",
        "type" => "字段 生成类型，single 单表CRUD，tree 树表CRUD，parent_sub父子表CRUD:type",
        "generate_type" => "字段 0 压缩包下载 1 生成到模块:generate_type",
        "generate_menus" => "字段 生成菜单列表:generate_menus",
        "build_menu" => "字段 是否构建菜单:build_menu",
        "component_type" => "字段 组件显示方式:component_type",
        "options" => "字段 其他业务选项:options",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['table_name', 'table_comment', 'module_name', 'namespace', 'menu_name', 'belong_menu_id', 'package_name', 'type', 'generate_type', 'generate_menus', 'build_menu', 'component_type', 'options',],
        "update" => ["id"],
        "generate_update" => ["id","generate_type","build_menu","generate_menus","menu_name","module_name","table_comment","table_name","type","component_type","columns","belong_menu_id","options"],
        "load_table" => ["names"]
    ];
}
