<?php


namespace plugin\stone\app\controller\system;


use plugin\stone\app\service\system\SystemDeptService;
use plugin\stone\app\service\system\SystemPostService;
use plugin\stone\app\service\system\SystemRoleService;
use plugin\stone\app\service\system\SystemUserService;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class CommonController extends NyuwaController
{
    /**
     * @var SystemUserService
     */
    #[Inject]
    protected SystemUserService $userService;

    /**
     * @var SystemDeptService
     */
    #[Inject]
    protected SystemDeptService $deptService;

    /**
     * @var SystemRoleService
     */
    #[Inject]
    protected SystemRoleService $roleService;

    /**
     * @var SystemPostService
     */
    #[Inject]
    protected SystemPostService $postService;

    /**
     * 获取用户列表
     */
    public function getUserList(Request $request): NyuwaResponse
    {
        return $this->success($this->userService->getPageList($request->all()));
    }

    /**
     * 获取部门树列表
     */
    #[GetMapping("getDeptTreeList")]
    public function getDeptTreeList(): NyuwaResponse
    {
        return $this->success($this->deptService->getSelectTree());
    }

    /**
     * 获取角色列表
     */
    #[GetMapping("getRoleList")]
    public function getRoleList(): NyuwaResponse
    {
        return $this->success($this->roleService->getList());
    }

    /**
     * 获取岗位列表
     */
    #[GetMapping("getPostList")]
    public function getPostList(): NyuwaResponse
    {
        return $this->success($this->postService->getList());
    }
}