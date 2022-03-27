<?php


namespace nyuwa\traits;


use app\admin\model\system\SystemDept;
use app\admin\model\system\SystemRole;
use app\admin\model\system\SystemUser;
use Illuminate\Database\Eloquent\Builder;
use nyuwa\exception\NyuwaException;

trait ModelMacroTrait
{
    /**
     * 注册自定义方法
     */
    private function registerUserDataScope()
    {
        // 数据权限方法
        $model = $this;
        Builder::macro('userDataScope', function(?int $userid = null) use($model)
        {
            $userid = is_null($userid) ? (int) nyuwa_user()->getId() : $userid;

            if (empty($userid)) {
                throw new NyuwaException('Data Scope missing user_id');
            }

            /* @var Builder $this */
            if ($userid == env('SUPER_ADMIN')) {
                return $this;
            }

            if (!in_array('created_by', $model->getFillable())) {
                return $this;
            }

            $dataScope = new class($userid, $this)
            {
                // 用户ID
                protected $userid;

                // 查询构造器
                protected $builder;

                // 数据范围用户ID列表
                protected $userIds = [];

                public function __construct(int $userid, Builder $builder)
                {
                    $this->userid  = $userid;
                    $this->builder = $builder;
                }

                /**
                 * @return Builder
                 */
                public function execute(): Builder
                {
                    $this->getUserDataScope();
                    if (empty($this->userIds)) {
                        return $this->builder;
                    } else {
                        array_push($this->userIds, $this->userid);
                        return $this->builder->whereIn('created_by', array_unique($this->userIds));
                    }
                }

                protected function getUserDataScope(): void
                {
                    $userModel = SystemUser::find($this->userid, ['id', 'dept_id']);
                    $roles = $userModel->roles()->get(['id', 'data_scope']);

                    foreach ($roles as $role) {
                        switch ($role->data_scope) {
                            case SystemRole::ALL_SCOPE:
                                // 如果是所有权限，跳出所有循环
                                break 2;
                            case SystemRole::CUSTOM_SCOPE:
                                // 自定义数据权限
                                $deptIds = $role->depts()->pluck('id')->toArray();
                                $this->userIds = array_merge(
                                    $this->userIds,
                                    SystemUser::query()->whereIn('dept_id', $deptIds)->pluck('id')->toArray()
                                );
                                break;
                            case SystemRole::SELF_DEPT_SCOPE:
                                // 本部门数据权限
                                $this->userIds = array_merge(
                                    $this->userIds,
                                    SystemUser::query()->where('dept_id', $userModel->dept_id)->pluck('id')->toArray()
                                );
                                break;
                            case SystemRole::DEPT_BELOW_SCOPE:
                                // 本部门及子部门数据权限
                                $deptIds = SystemDept::query()->where('level', 'like', '%'.$userModel->dept_id.'%')->pluck('id')->toArray();
                                $deptIds[] = $userModel->dept_id;
                                $this->userIds = array_merge(
                                    $this->userIds,
                                    SystemUser::query()->whereIn('dept_id', $deptIds)->pluck('id')->toArray()
                                );
                                break;
                            case SystemRole::SELF_SCOPE:
                            default:
                                break;
                        }
                    }
                }
            };

            return $dataScope->execute();
        });
    }

    /**
     * 注册数据字段典转换逻辑
     */
    private function registerTransformDataScope(){
        $model = $this;
        $ignoreTable = ["system_dict_data","system_dict_type","system_dict_field"];
        Builder::macro('transformDataScope', function(?int $userid = null) use($ignoreTable, $model)
        {
            var_dump("加入数据转换宏定义");
            //查找该模型是否添加了字段字典。有则取出。
            $tableName = $model->getTable();
            var_dump("加入数据转换宏定义：获取表 $tableName");
            if (in_array($tableName,$ignoreTable)){
                return $this;
            }
            var_dump("加入数据转换宏定义：追加字段 $tableName");
//            $model->append("ceshi");
//            $model->append("ceshi1");
            $model->setAttribute('ceshi', "ceshi");
            $model->setAttribute('ceshi1', "ceshi1");
            return $this;
        });
    }

    /**
     * Description:注册常用自定义方法
     * User:mike
     */
    private function registerBase()
    {
        //添加andFilterWhere()方法
        Builder::macro('andFilterWhere', function ($key, $operator, $value = NULL) {
            if ($value === '' || $value === '%%' || $value === '%') {
                return $this;
            }
            if ($operator === '' || $operator === '%%' || $operator === '%') {
                return $this;
            }
            if($value === NULL){
                return $this->where($key, $operator);
            }else{
                return $this->where($key, $operator, $value);
            }
        });

        //添加orFilterWhere()方法
        Builder::macro('orFilterWhere', function ($key, $operator, $value = NULL) {
            if ($value === '' || $value === '%%' || $value === '%') {
                return $this;
            }
            if ($operator === '' || $operator === '%%' || $operator === '%') {
                return $this;
            }
            if($value === NULL){
                return $this->orWhere($key, $operator);
            }else{
                return $this->orWhere($key, $operator, $value);
            }
        });
    }
}
