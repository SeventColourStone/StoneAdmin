<?php

declare(strict_types=1);

namespace plugin\stone\app\model\system;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\stone\nyuwa\NyuwaModel;
/**
 * 角色表
 * Class SystemRole
 * @package plugin\stone\app\model\core
 *
 * @property int $id 主键，角色ID
 * @property string $name 角色名称
 * @property string $code 角色代码
 * @property string $data_scope 数据范围（0：全部数据权限 1：自定义数据权限 2：本部门数据权限 3：本部门及以下数据权限 4：本人数据权限）
 * @property string $status 状态 (0正常 1停用)
 * @property int $sort 排序
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property-read Collection|SystemDept[] $depts
 * @property-read Collection|SystemMenu[] $menus
 * @property-read Collection|SystemUser[] $users
 */
class SystemRole extends NyuwaModel
{
    use SoftDeletes;
    public $incrementing = false;
    // 所有
    public const ALL_SCOPE = '0';
    // 自定义
    public const CUSTOM_SCOPE = '1';
    // 本部门
    public const SELF_DEPT_SCOPE = '2';
    // 本部门及子部门
    public const DEPT_BELOW_SCOPE = '3';
    // 本人
    public const SELF_SCOPE = '4';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_role';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','code','data_scope','status','sort','created_by','updated_by','created_at','updated_at','deleted_at','remark',];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];



    /**
     * 通过中间表获取菜单
     */
    public function menus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SystemMenu::class, 'system_role_menu', 'role_id', 'menu_id');
    }
    /**
     * 通过中间表获取用户
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SystemUser::class, 'system_user_role', 'role_id', 'user_id');
    }
    /**
     * 通过中间表获取部门
     */
    public function depts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SystemDept::class, 'system_role_dept', 'role_id', 'dept_id');
    }
}
