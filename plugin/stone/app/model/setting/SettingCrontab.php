<?php

declare(strict_types=1);

namespace plugin\stone\app\model\setting;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\stone\nyuwa\NyuwaModel;
/**
 * 定时任务信息表
 * Class SettingCrontab
 * @package plugin\stone\app\model\core
 *
 * @property int $id 主键
 * @property string $name 任务名称
 * @property string $type 任务类型 (1 command, 2 class, 3 url, 4 eval)
 * @property string $target 调用任务字符串
 * @property string $parameter 调用任务参数
 * @property string $rule 任务执行表达式
 * @property string $singleton 是否单次执行 (0 是 1 不是)
 * @property string $status 状态 (0正常 1停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $remark 备注
 * @property-read Collection|SettingCrontabLog[] $logs
 */
class SettingCrontab extends NyuwaModel
{
    // 命令任务
    public const COMMAND_CRONTAB = '1';
    // 类任务
    public const CLASS_CRONTAB = '2';
    // URL任务
    public const URL_CRONTAB = '3';
    // EVAL 任务
    public const EVAL_CRONTAB = '4';
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_crontab';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'type', 'target', 'parameter', 'rule', 'singleton', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'remark'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_by' => 'integer', 'updated_by' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    /**
     * 关联字典任务日志表
     */
    public function logs()
    {
        return $this->hasMany(SettingCrontabLog::class, 'crontab_id', 'id');
    }


}
