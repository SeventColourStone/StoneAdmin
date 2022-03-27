<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemNoticeValidate extends NyuwaValidate
{

    protected $rule = [
        "click_num" => "required|numeric",  //浏览次数
        "content" => "required",  //公告内容
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "deleted_at" => "required|date",  //删除时间
        "id" => "required|numeric",  //主键
        "message_id" => "required|numeric",  //消息ID
        "remark" => "required|max:255",  //备注
        "title" => "required|max:255",  //标题
        "type" => "required|max:1",  //公告类型（1通知 2公告）
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者

        'users' => 'required|array',
    ];

    protected $customAttributes = [
        "click_num" => "字段 浏览次数:click_num",
        "content" => "字段 公告内容:content",
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "deleted_at" => "字段 删除时间:deleted_at",
        "id" => "字段 主键:id",
        "message_id" => "字段 消息ID:message_id",
        "remark" => "字段 备注:remark",
        "title" => "字段 标题:title",
        "type" => "字段 公告类型（1通知 2公告）:type",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
    ];

    protected $scene = [
        "key" => ["id"],
        "create"=>["title","type","content"],
        "update"=>["id","title","type","content"],
        "sendPrivateMessage"=>["title","type","content"],//发私信
    ];

}
