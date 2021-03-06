<?php


namespace app\admin\model\system;


use Illuminate\Database\Eloquent\SoftDeletes;
use nyuwa\NyuwaModel;

/**
 * @property int $id 主键
 * @property int $type_id 字典类型ID
 * @property string $label 字典标签
 * @property string $value 字典值
 * @property string $code 字典标示
 * @property int $sort 排序
 * @property string $status 状态 (0正常 1停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 */
class SystemDictData extends NyuwaModel
{
    use SoftDeletes;
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_dict_data';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'type_id', 'label', 'value', 'code', 'sort', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at', 'remark'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'type_id' => 'string', 'sort' => 'integer', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

}
