<?php

declare(strict_types=1);


namespace app\admin\validate\core;


use nyuwa\NyuwaValidate;

class SystemNoticeValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "message_id" => "required",  //消息ID
        "title" => "required|max:255",  //标题
        "type" => "required|string",  //公告类型
        
        "click_num" => "required",  //浏览次数
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "message_id" => "字段 消息ID:message_id",
        "title" => "字段 标题:title",
        "type" => "字段 公告类型:type",
        "content" => "字段 公告内容:content",
        "click_num" => "字段 浏览次数:click_num",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['message_id', 'title', 'type', 'content', 'click_num',],
        "update" => ["id"],
        "status" => ["id","status"],
    ];
}
