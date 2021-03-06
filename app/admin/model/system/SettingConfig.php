<?php


namespace app\admin\model\system;


use nyuwa\NyuwaModel;

/**
 * @property string $key 配置键名
 * @property string $value 配置值
 * @property string $name 配置名称
 * @property string $group_name 组名称
 * @property int $sort 排序
 * @property string $remark 备注
 */
class SettingConfig extends NyuwaModel
{
    public $incrementing = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_config';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'value', 'name', 'group_name', 'sort', 'remark'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['sort' => 'integer'];
}
