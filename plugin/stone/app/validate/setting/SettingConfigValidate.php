<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\setting;


use plugin\stone\nyuwa\NyuwaValidate;

class SettingConfigValidate extends NyuwaValidate
{
    protected $rule = [
        "key" => "required|max:32",  //配置键名
        "value" => "required|max:255",  //配置值
        "name" => "required|max:255",  //配置名称
        "group_name" => "required|max:100",  //组名称
        "sort" => "required|numeric",  //排序
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "key" => "字段 配置键名:key",
        "value" => "字段 配置值:value",
        "name" => "字段 配置名称:name",
        "group_name" => "字段 组名称:group_name",
        "sort" => "字段 排序:sort",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['key', 'value', 'name', 'group_name', 'sort',],
        "update" => ["id"],
        "updated" => ['key', 'value',"name","group_name"],
    ];
}
