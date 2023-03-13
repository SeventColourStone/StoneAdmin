<?php

declare(strict_types=1);

namespace plugin\stone\app\model\system;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\stone\nyuwa\NyuwaModel;
/**
 * 字典表转换
 * Class SystemDictField
 * @package plugin\stone\app\model\core
 *
 * @property int $id 主键
 * @property int $dict_type_id system_dict_type 关联id
 * @property string $table_name 表名
 * @property string $field_name 字段名
 * @property string $default_val 兜底值
 * @property string $status 状态 (0正常 1停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property-read SystemDictType $dictType
 */
class SystemDictField extends NyuwaModel
{
    use SoftDeletes;
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_dict_field';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dict_type_id','table_name','field_name','default_val','status','view_type','created_by','updated_by','created_at','updated_at','deleted_at','remark',];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    /**
     * 关联字典类型表表
     */
    public function dictType()
    {
        return $this->hasOne(SystemDictType::class, 'id', 'dict_type_id');
    }
}
