<?php
namespace plugin\admin\app\common;


use plugin\admin\app\model\Admin;
use plugin\admin\app\model\AdminRole;
use plugin\admin\app\model\Role;
use plugin\admin\app\model\Rule;

class Auth
{
    /**
     * 获取权限范围内的所有角色id
     * @param bool $with_self
     * @return array
     */
    public static function getScopeRoleIds(bool $with_self = false): array
    {
        if (!$admin = admin()) {
            return [];
        }
        $role_ids = $admin['roles'];
        $rules = Role::whereIn('id', $role_ids)->pluck('rules')->toArray();
        if ($rules && in_array('*', $rules)) {
            return Role::pluck('id')->toArray();
        }

        $roles = Role::get();
        $tree = new Tree($roles);
        $descendants = $tree->getDescendant($role_ids, $with_self);
        return array_column($descendants, 'id');
    }

    /**
     * 获取权限范围内的所有管理员id
     * @param bool $with_self
     * @return array
     */
    public static function getScopeAdminIds(bool $with_self = false): array
    {
        $role_ids = static::getScopeRoleIds($with_self);
        return AdminRole::whereIn('role_id', $role_ids)->pluck('admin_id')->toArray();
    }

    /**
     * 是否是超级管理员
     * @param int $admin_id
     * @return bool
     */
    public static function isSupperAdmin(int $admin_id = 0): bool
    {
        if (!$admin_id) {
            if (!$roles = admin('roles')) {
                return false;
            }
        } else {
            $roles = AdminRole::where('admin_id', $admin_id)->pluck('role_id');
        }
        $rules = Role::whereIn('id', $roles)->pluck('rules');
        return $rules && in_array('*', $rules->toArray());
    }

}