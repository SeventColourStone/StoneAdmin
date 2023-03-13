<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemUser;
use plugin\stone\app\model\system\SystemDept;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;
use plugin\stone\nyuwa\NyuwaModel;

/**
 * 用户表
 * Class SystemUserMapper
 * @package plugin\stone\app\mapper\core
 */
class SystemUserMapper extends AbstractMapper
{
    /**
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemUser::class;
    }

    public function checkUserByUsername($username)
    {
        return $this->model::query()->where('username', $username)->firstOrFail();
    }

    /**
     * 通过用户名检查是否存在
     * @param string $username
     * @return bool
     */
    public function existsByUsername(string $username): bool
    {
        return $this->model::query()->where('username', $username)->exists();
    }

    /**
     * 检查用户密码
     * @param String $password
     * @param string $hash
     * @return bool
     */
    public function checkPass(String $password, string $hash): bool
    {
        return $this->model::passwordVerify($password, $hash);
    }

    /**
     * 新增用户
     * @param array $data
     * @return string
     * @Transaction
     */
    public function save(array $data): string
    {
        $role_ids = $data['role_ids'] ?? [];
        $post_ids = $data['post_ids'] ?? [];

        $this->filterExecuteAttributes($data, true);

        $user = $this->model::create($data);
        $user->roles()->sync($role_ids, false);
        $user->posts()->sync($post_ids, false);
        return $user->id;
    }

    /**
     * 更新用户
     * @param string $id
     * @param array $data
     * @return bool
     * @Transaction
     */
    public function update(string $id, array $data): bool
    {
        $role_ids = $data['role_ids'] ?? [];
        $post_ids = $data['post_ids'] ?? [];
        $this->filterExecuteAttributes($data, true);

        $result = parent::update($id, $data);
        $user = $this->model::find($id);
        if ($user && $result) {
            !empty($role_ids) && $user->roles()->sync($role_ids);
            $user->posts()->sync($post_ids);
            return true;
        }
        return false;
    }

    /**
     * 真实批量删除用户
     * @param array $ids
     * @return bool
     * @Transaction
     */
    public function realDelete(array $ids): bool
    {
        foreach ($ids as $id) {
            $user = $this->model::withTrashed()->find($id);
            if ($user) {
                $user->roles()->detach();
                $user->posts()->detach();
                $user->forceDelete();
            }
        }
        return true;
    }

    /**
     * 获取用户信息
     * @param string $id
     * @return NyuwaModel
     */
    public function read(string $id): ?NyuwaModel
    {
        $user = $this->model::find($id);
        if ($user) {
            $user->setAttribute('roleList', $user->roles()->get() ?: []);
            $user->setAttribute('postList', $user->posts()->get() ?: []);
        }
        return $user;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['dept_id'])) {
            $query->whereIn('dept_id', explode(',', $params['dept_id']));
        }
        if (isset($params['username'])) {
            $query->where('username', 'like', '%'.$params['username'].'%');
        }
        if (isset($params['nickname'])) {
            $query->where('nickname', 'like', '%'.$params['nickname'].'%');
        }
        if (isset($params['phone'])) {
            $query->where('phone', '=', $params['phone']);
        }
        if (isset($params['email'])) {
            $query->where('email', '=', $params['email']);
        }
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['filterSuperAdmin'])) {
            $query->whereNotIn('id', [env('SUPER_ADMIN')]);
        }

        if (isset($params['minDate']) && isset($params['maxDate'])) {
            $query->whereBetween(
                'created_at',
                [$params['minDate'] . ' 00:00:00', $params['maxDate'] . ' 23:59:59']
            );
        }
        if (isset($params['userIds'])) {
            $query->whereIn('id', $params['userIds']);
        }

        if (isset($params['showDept'])) {
            $isAll = $params['showDeptAll'] ?? false;

            $query->with(['dept' => function($query) use($isAll){
                /* @var Builder $query*/
                $query->where('status', SystemDept::ENABLE);
                return $isAll ? $query->select(['*']) : $query->select(['id', 'name']);
            }]);
        }

        if (isset($params['role_id'])) {
            $tablePrefix = env('DB_PREFIX');
            $query->whereRaw(
                "id IN ( SELECT user_id FROM {$tablePrefix}system_user_role WHERE role_id = ? )",
                [ $params['role_id'] ]
            );
        }

        if (isset($params['post_id'])) {
            $tablePrefix = env('DB_PREFIX');
            $query->whereRaw(
                "id IN ( SELECT user_id FROM {$tablePrefix}system_user_post WHERE post_id = ? )",
                [ $params['post_id'] ]
            );
        }

        return $query;
    }

    /**
     * 初始化用户密码
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function initUserPassword(int $id, string $password): bool
    {
        $model = $this->model::find($id);
        if ($model) {
            $model->password = $password;
            return $model->save();
        }
        return false;
    }
}
