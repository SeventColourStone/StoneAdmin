<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SettingConfigValidate extends NyuwaValidate
{

    protected $rule = [
        "group_name" => "required|max:100",  //组名称
        "key" => "required|max:255",  //配置键名
        "name" => "required|max:255",  //配置名称
        "remark" => "required|max:255",  //备注
        "sort" => "required|numeric",  //排序
        "value" => "required|max:255",  //配置值


    ];

    protected $customAttributes = [
        "group_name" => "字段 组名称:group_name",
        "key" => "字段 配置键名:key",
        "name" => "字段 配置名称:name",
        "remark" => "字段 备注:remark",
        "sort" => "字段 排序:sort",
        "value" => "字段 配置值:value",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["key","value","name","group_name","remark"],
        "update" => ["id","key","value","name","group_name","remark"]
    ];
}
