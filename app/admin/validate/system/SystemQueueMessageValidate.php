<?php

declare(strict_types=1);


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemQueueMessageValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "content_type" => "required|max:64",  //内容类型
        "title" => "required|max:255",  //消息标题
        
        "send_by" => "required",  //发送人
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "remark" => "required|max:255",  //备注

        "users" => "required|array"
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "content_type" => "字段 内容类型:content_type",
        "title" => "字段 消息标题:title",
        "content" => "字段 消息内容:content",
        "send_by" => "字段 发送人:send_by",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['content_type', 'title', 'content', 'send_by',],
        "update" => ["id"],

        "send_private_message" => ["title","users","content"],
    ];
}
