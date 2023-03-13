<?php

declare(strict_types=1);


namespace plugin\stone\app\validate\system;


use plugin\stone\nyuwa\NyuwaValidate;

class SystemUploadfileValidate extends NyuwaValidate
{
    protected $rule = [
        "id" => "required",  //主键
        "storage_mode" => "required|string",  //状态 (1 本地 2 阿里云 3 七牛云 4 腾讯云)
        "origin_name" => "required|max:255",  //原文件名
        "object_name" => "required|max:50",  //新文件名
        "mime_type" => "required|max:255",  //资源类型
        "storage_path" => "required|max:100",  //存储目录
        "suffix" => "required|max:10",  //文件后缀
        "size_byte" => "required",  //字节数
        "size_info" => "required|max:50",  //文件大小
        "url" => "required|max:255",  //url地址
        "created_by" => "required",  //创建者
        "updated_by" => "required",  //更新者
        "created_at" => "required|date",  //创建时间
        "updated_at" => "required|date",  //更新时间
        "deleted_at" => "required|date",  //删除时间
        "remark" => "required|max:255",  //备注

        'file' => 'required|mimes:txt,doc,docx,xls,xlsx,ppt,pptx,rar,zip,7z,gz,pdf,wps,md',
        'path' => 'max:30',

//        'image' => 'required|image',
        'url'   => 'required',
    ];

    protected $customAttributes = [
        "id" => "字段 主键:id",
        "storage_mode" => "字段 状态 (1 本地 2 阿里云 3 七牛云 4 腾讯云):storage_mode",
        "origin_name" => "字段 原文件名:origin_name",
        "object_name" => "字段 新文件名:object_name",
        "mime_type" => "字段 资源类型:mime_type",
        "storage_path" => "字段 存储目录:storage_path",
        "suffix" => "字段 文件后缀:suffix",
        "size_byte" => "字段 字节数:size_byte",
        "size_info" => "字段 文件大小:size_info",
        "url" => "字段 url地址:url",
        "created_by" => "字段 创建者:created_by",
        "updated_by" => "字段 更新者:updated_by",
        "created_at" => "字段 创建时间:created_at",
        "updated_at" => "字段 更新时间:updated_at",
        "deleted_at" => "字段 删除时间:deleted_at",
        "remark" => "字段 备注:remark",
    ];

    protected $scene = [
        "key" => ["id"],
        "create" => ['storage_mode', 'origin_name', 'object_name', 'mime_type', 'storage_path', 'suffix', 'size_byte', 'size_info', 'url',],
        "update" => ["id"],

        "upload_file" => ["file"],
        "upload_image" => [],
        "network_image" => ["url"],
    ];
}
