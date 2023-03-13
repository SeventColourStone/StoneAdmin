<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\setting;


use plugin\stone\nyuwa\NyuwaValidate;

class SettingCrontabValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "name" => "required|max:100",  //任务名称
        "type" => "required|string",  //任务类型 (1 command, 2 class, 3 url, 4 eval)
        "target" => "required|max:500",  //调用任务字符串
        "parameter" => "required|max:1000",  //调用任务参数
        "rule" => "required|max:32",  //任务执行表达式
        "singleton" => "required|string",  //是否单次执行 (0 是 1 不是)
        "status" => "required|string",  //状态 (0正常 1停用)
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "name" => "字段 任务名称:name",
        "type" => "字段 任务类型 (1 command, 2 class, 3 url, 4 eval):type",
        "target" => "字段 调用任务字符串:target",
        "parameter" => "字段 调用任务参数:parameter",
        "rule" => "字段 任务执行表达式:rule",
        "singleton" => "字段 是否单次执行 (0 是 1 不是):singleton",
        "status" => "字段 状态 (0正常 1停用):status",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['name',],
        "update" => ["id","name"],
    ];
}
