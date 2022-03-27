<?php


namespace app\admin\validate\system;


use nyuwa\NyuwaValidate;

class SystemUserValidate extends NyuwaValidate
{
    protected $rule = [
        "avatar" => "string",  //用户头像
        "backend_setting" => "string",  //后台设置数据
        "created_at" => "required|date",  //创建时间
        "created_by" => "required|numeric",  //创建者
        "dashboard" => "string",  //后台首页类型
        "deleted_at" => "required|date",  //删除时间
        "dept_id" => "required",  //部门ID
        "email" => "string",  //用户邮箱
        "id" => "required",  //用户ID，主键
        "login_ip" => "string",  //最后登陆IP
        "login_time" => "required|date",  //最后登陆时间
        "nickname" => "string",  //用户昵称
        "password" => "required|string",  //密码
        "phone" => "string",  //手机
        "remark" => "string",  //备注
        "signed" => "string",  //个人签名
        "status" => "required|string",  //状态 (0正常 1停用)
        "updated_at" => "required|date",  //更新时间
        "updated_by" => "required|numeric",  //更新者

        "user_type" => " string",
        "username" => " required|max:20",
        'role_ids' => 'required',
        'code'     => 'required',
        'newPassword' => 'required|confirmed',
        'newPassword_confirmation' => 'required',
        'oldPassword' => 'required',
        "ids" => "required",
    ];

    protected $customAttributes = [
        "avatar" => "字段 用户头像:avatar",
        "backend_setting" => "字段 后台设置数据:backend_setting",
        "created_at" => "字段 创建时间:created_at",
        "created_by" => "字段 创建者:created_by",
        "dashboard" => "字段 后台首页类型:dashboard",
        "deleted_at" => "字段 删除时间:deleted_at",
        "dept_id" => "字段 部门ID:dept_id",
        "email" => "字段 用户邮箱:email",
        "id" => "字段 用户ID，主键:id",
        "login_ip" => "字段 最后登陆IP:login_ip",
        "login_time" => "字段 最后登陆时间:login_time",
        "nickname" => "字段 用户昵称:nickname",
        "password" => "字段 密码:password",
        "phone" => "字段 手机:phone",
        "remark" => "字段 备注:remark",
        "signed" => "字段 个人签名:signed",
        "status" => "字段 状态 (0正常 1停用):status",
        "updated_at" => "字段 更新时间:updated_at",
        "updated_by" => "字段 更新者:updated_by",
        "user_type" => "字段 用户类型：(100系统用户):user_type",
        "username" => "字段 用户名:username",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ["username","password","dept_id","role_ids"],
        "homoPage" => ["id","dashboard"],
        "login" => ["username","password","code"],
        "password" => ["newPassword","newPassword_confirmation","oldPassword"],
        "status" => ["id","status"],
        "update" => ["id","username","dept_id","role_ids"],
    ];


}
