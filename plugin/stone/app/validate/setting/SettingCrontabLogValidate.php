<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\setting;


use plugin\stone\nyuwa\NyuwaValidate;

class SettingCrontabLogValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "crontab_id" => "required",  //任务ID
        "name" => "required|max:255",  //任务名称
        "target" => "required|max:500",  //任务调用目标字符串
        "parameter" => "required|max:1000",  //任务调用参数
        "exception_info" => "required|max:2000",  //异常信息
        "status" => "required|string",  //执行状态 (0成功 1失败)
        "created_at" => "required|date",  //创建时间
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "crontab_id" => "字段 任务ID:crontab_id",
        "name" => "字段 任务名称:name",
        "target" => "字段 任务调用目标字符串:target",
        "parameter" => "字段 任务调用参数:parameter",
        "exception_info" => "字段 异常信息:exception_info",
        "status" => "字段 执行状态 (0成功 1失败):status",
        "created_at" => "字段 创建时间:created_at",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['crontab_id', 'name', 'target', 'parameter', 'exception_info', 'status',],
        "update" => ["id"],
    ];
}
