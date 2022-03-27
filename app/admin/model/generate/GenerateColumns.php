<?php

declare(strict_types=1);

namespace app\admin\model\generate;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use nyuwa\NyuwaModel;
/**
 * 代码生成业务字段信息表
 * Class GenerateColumns
 * @package app\admin\model\generate
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
class GenerateColumns extends NyuwaModel
{
    use SoftDeletes;
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'generate_columns';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['table_id','column_name','column_comment','data_type','date_max_len','is_pk','is_required','is_insert','is_edit','is_list','is_query','query_type','view_type','dict_type','sort','created_by','updated_by','created_at','updated_at','deleted_at','remark',];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

}
