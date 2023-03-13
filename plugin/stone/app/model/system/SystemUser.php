<?php

declare(strict_types=1);

namespace plugin\stone\app\model\system;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\stone\nyuwa\NyuwaModel;
/**
 * 用户表
 * Class SystemUser
 * @package plugin\stone\app\model\core
 *
 * @property int $id 用户ID，主键
 * @property string $username 用户名
 * @property string $user_type 用户类型：(100系统用户)
 * @property string $nickname 用户昵称
 * @property string $phone 手机
 * @property string $email 用户邮箱
 * @property string $avatar 用户头像
 * @property string $signed 个人签名
 * @property string $dashboard 后台首页类型
 * @property int $dept_id 部门ID
 * @property string $status 状态 (0正常 1停用)
 * @property string $login_ip 最后登陆IP
 * @property string $login_time 最后登陆时间
 * @property string $backend_setting 后台设置数据
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property-read SystemDept $dept
 * @property-read Collection|SystemPost[] $posts
 * @property-read Collection|SystemRole[] $roles
 * @property-write mixed $password 密码
 */
class SystemUser extends NyuwaModel
{

    use SoftDeletes;
    public const USER_NORMAL = 0;
    public const USER_BAN = 1;
    /**
     * 系统用户
     */
    public const TYPE_SYS_USER = '100';
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'username', 'password', 'user_type', 'nickname', 'phone', 'email', 'avatar', 'signed', 'dashboard', 'dept_id', 'status', 'login_ip', 'login_time', 'backend_setting', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at', 'remark'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'dept_id' => 'string', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'backend_setting' => 'array'];

    /**
     * 通过中间表关联角色
     * @return
     */
    public function roles()
    {
        return $this->belongsToMany(SystemRole::class, 'system_user_role', 'user_id', 'role_id');
    }
    /**
     * 通过中间表关联岗位
     * @return
     */
    public function posts()
    {
        return $this->belongsToMany(SystemPost::class, 'system_user_post', 'user_id', 'post_id');
    }
    /**
     * 关联部门
     * @return
     */
    public function dept()
    {
        return $this->hasOne(SystemDept::class, 'id', 'dept_id');
    }
    /**
     * 密码加密
     * @param $value
     * @return void
     */
    public function setPasswordAttribute($value) : void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }
    /**
     * 验证密码
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function passwordVerify($password, $hash) : bool
    {
        return password_verify($password, $hash);
    }
}
