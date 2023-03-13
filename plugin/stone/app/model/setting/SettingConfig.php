<?php

declare(strict_types=1);

namespace plugin\stone\app\model\setting;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\stone\nyuwa\NyuwaModel;
/**
 * 参数配置信息表
 * Class SettingConfig
 * @package plugin\stone\app\model\core
 *
 * @property string $id 主键
 * @property string $status 状态 (0正常 1停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
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
    protected $fillable = ['key','value','name','group_name','sort','remark',];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    /* 一些关联表的关联操作 */



}
