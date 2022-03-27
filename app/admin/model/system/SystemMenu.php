<?php


declare (strict_types=1);
namespace app\admin\model\system;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use nyuwa\NyuwaModel;

/**
 * @property int $id 主键
 * @property int $parent_id 父ID
 * @property string $level 组级集合
 * @property string $name 菜单名称
 * @property string $code 菜单标识代码
 * @property string $icon 菜单图标
 * @property string $route 路由地址
 * @property string $component 组件路径
 * @property string $redirect 跳转地址
 * @property string $is_hidden 是否隐藏 (0是 1否)
 * @property string $type 菜单类型, (M菜单 B按钮 L链接 I iframe)
 * @property string $open_type 当 type 为 1 时，openType 生效，_iframe 正常打开 _blank 新建浏览器标签页
 * @property string $status 状态 (0正常 1停用)
 * @property int $sort 排序
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property-read Collection|SystemRole[] $roles
 */
class SystemMenu extends NyuwaModel
{

    use SoftDeletes;
    public $incrementing = true;
    /**
     * 类型
     */
    public const LINK = 'L';
    public const IFRAME = 'I';
    public const MENUS_LIST = 'M';
    public const DIRECTORY_LIST = 'D';
    public const BUTTON = 'B';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'parent_id', 'level', 'name', 'code', 'icon', 'route', 'component', 'redirect', 'is_hidden', 'type','open_type', 'status', 'sort', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at', 'remark'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'parent_id' => 'string', 'sort' => 'integer', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];



    /**
     * 通过中间表获取角色
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SystemRole::class, 'system_role_menu', 'menu_id', 'role_id');
    }
}
