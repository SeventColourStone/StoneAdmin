<?php

declare(strict_types=1);


namespace app\admin\validate\generate;


use nyuwa\NyuwaValidate;

class GenerateTablesValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "table_name" => "required|max:200",  //表名称
        "table_comment" => "required|max:500",  //表注释
        "module_name" => "required|max:100",  //所属模块
        "namespace" => "required|max:255",  //命名空间
        "menu_name" => "required|max:100",  //生成菜单名
        "belong_menu_id" => "required",  //所属菜单
        "package_name" => "required|max:100",  //控制器包名
        "type" => "required|max:100",  //生成类型
        "generate_type" => "required|string",  //生成方式
        "options" => "required|max:1500",  //其他业务选项
        "files" => "required|max:1500",  //生成文件路径
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
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
        "type" => "字段 生成类型:type",
        "generate_type" => "字段 生成方式:generate_type",
        "options" => "字段 其他业务选项:options",
        "files" => "字段 生成文件路径:files",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['table_name', 'table_comment', 'module_name', 'namespace', 'menu_name', 'belong_menu_id', 'package_name', 'type', 'generate_type', 'options', 'files', 'remark',],
        "update" => ["id",'table_name', 'table_comment', 'module_name', 'namespace', 'menu_name', 'belong_menu_id', 'package_name', 'type', 'generate_type', 'options', 'files', 'remark',],
        "status" => ["id","status"],
    ];
}
