<?php


namespace plugin\stone\nyuwa;


use DateTimeInterface;
use Godruoyi\Snowflake\Snowflake;
use plugin\stone\nyuwa\event\ModelSavedEvent;
use plugin\stone\nyuwa\helper\LoginUser;
//use plugin\stone\nyuwa\traits\ModelCacheTrait;
use plugin\stone\nyuwa\traits\ModelMacroTrait;
use support\Container;
use support\Model;
use Webman\App;

class NyuwaModel extends Model
{
    use ModelMacroTrait;

    /**
     * 模型的日期字段的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';


    /**
     * 模型的 "booted" 方法
     *
     * @return void
     */
    protected static function booted()
    {
        //统一的模型主键、有token认证的用户操作人插入处理
        static::saving(function ($modelInstant) {
            // 创建了数据时执行
            try {
                /*** @var LoginUser */
                $loginUser = nyuwa_app(LoginUser::class);
                $loginUser->check();

                // 设置创建人
                if ($modelInstant instanceof NyuwaModel &&
                    in_array('created_by', $modelInstant->getFillable()) &&
                    is_null($modelInstant->created_by)
                ) {
                    $modelInstant->created_by = $loginUser->getId();
                }
                // 设置更新人
                if ($modelInstant instanceof NyuwaModel && in_array('updated_by', $modelInstant->getFillable())) {
                    $modelInstant->updated_by = $loginUser->getId();
                }
            } catch (\Throwable $e) {
                // 设置创建人
                if ($modelInstant instanceof NyuwaModel &&
                    in_array('created_by', $modelInstant->getFillable()) &&
                    is_null($modelInstant->created_by)
                ) {
                    $modelInstant->created_by = 1;
                }
                // 设置更新人
                if ($modelInstant instanceof NyuwaModel && in_array('updated_by', $modelInstant->getFillable())) {
                    $modelInstant->updated_by = 1;
                }
            }

            // 生成ID
            if ($modelInstant instanceof NyuwaModel &&
                !$modelInstant->incrementing &&
                $modelInstant->getPrimaryKeyType() === 'int' &&
                empty($modelInstant->{$modelInstant->getKeyName()})
            ) {
                //整形未设置自增主键使用snowflake
                $modelInstant->setPrimaryKeyValue(snowflake_id());
            }
        });
    }

    /**
     * 隐藏的字段列表
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * 状态
     */
    public const ENABLE = 0;
    public const DISABLE = 1;

    /**
     * 默认每页记录数
     */
    public const PAGE_SIZE = 15;

    /**
     * MineModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        //注册常用方法
        $this->registerBase();
        //注册用户数据权限方法
        $this->registerUserDataScope();
        //注册用户转换方法
        $this->registerTransformDataScope();
    }

    /**
     * 设置主键的值
     * @param string | int $value
     */
    public function setPrimaryKeyValue($value): void
    {
        $this->{$this->primaryKey} = $value;
    }

    /**
     * @return string
     */
    public function getPrimaryKeyType(): string
    {
        return $this->keyType;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        return parent::save($options);
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        return parent::update($attributes, $options);
    }

    /**
     * @param array $models
     * @return NyuwaCollection
     */
    public function newCollection(array $models = []): NyuwaCollection
    {
        return new NyuwaCollection($models);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
