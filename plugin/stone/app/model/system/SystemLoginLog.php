<?php

declare (strict_types=1);

namespace plugin\stone\app\model\system;
use plugin\stone\nyuwa\NyuwaModel;

/**
 * @property int $id 主键
 * @property string $username 用户名
 * @property string $ip 登录IP地址
 * @property string $ip_location IP所属地
 * @property string $os 操作系统
 * @property string $browser 浏览器
 * @property string $status 登录状态 (0成功 1失败)
 * @property string $message 提示消息
 * @property string $login_time 登录时间
 * @property string $remark 备注
 */
class SystemLoginLog extends NyuwaModel
{
    public $incrementing = false;
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_login_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'username', 'ip', 'ip_location', 'os', 'browser', 'status', 'message', 'login_time', 'remark'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer'];
}