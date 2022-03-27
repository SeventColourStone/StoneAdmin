<?php

declare(strict_types=1);


namespace app\admin\validate\core;


use nyuwa\NyuwaValidate;

class SystemUserValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //用户ID
        "username" => "required|max:20",  //用户名
        "password" => "required|max:100",  //密码
        "user_type" => "required|max:3",  //用户类型
        "nickname" => "required|max:30",  //用户昵称
        "phone" => "required|max:11",  //手机
        "email" => "required|max:50",  //用户邮箱
        "avatar" => "required|max:255",  //用户头像
        "signed" => "required|max:255",  //个人签名
        "dashboard" => "required|max:100",  //后台首页类型
        "dept_id" => "required",  //部门ID
        "status" => "required|string",  //状态
        "login_ip" => "required|max:45",  //最后登陆IP
        "login_time" => "required|date",  //最后登陆时间
        "backend_setting" => "required|max:500",  //后台设置数据
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注
    ];

    protected $customAttributes = [
        "id" => "字段 用户ID:id",
        "username" => "字段 用户名:username",
        "password" => "字段 密码:password",
        "user_type" => "字段 用户类型:user_type",
        "nickname" => "字段 用户昵称:nickname",
        "phone" => "字段 手机:phone",
        "email" => "字段 用户邮箱:email",
        "avatar" => "字段 用户头像:avatar",
        "signed" => "字段 个人签名:signed",
        "dashboard" => "字段 后台首页类型:dashboard",
        "dept_id" => "字段 部门ID:dept_id",
        "status" => "字段 状态:status",
        "login_ip" => "字段 最后登陆IP:login_ip",
        "login_time" => "字段 最后登陆时间:login_time",
        "backend_setting" => "字段 后台设置数据:backend_setting",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['username', 'password', 'user_type', 'nickname', 'phone', 'email', 'avatar', 'signed', 'dashboard', 'dept_id', 'status', 'login_ip', 'login_time', 'backend_setting',],
        "update" => ["id"],
        "status" => ["id","status"],
    ];
}
