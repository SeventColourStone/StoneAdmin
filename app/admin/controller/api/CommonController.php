<?php


namespace app\admin\controller\api;


use app\admin\service\system\SystemDeptService;
use app\admin\service\system\SystemPostService;
use app\admin\service\system\SystemRoleService;
use app\admin\service\system\SystemUserService;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;

class CommonController extends NyuwaController
{

    /**
     * @Inject
     * @var SystemUserService
     */
    protected $userService;

    /**
     * @Inject
     * @var SystemDeptService
     */
    protected $deptService;

    /**
     * @Inject
     * @var SystemRoleService
     */
    protected $roleService;

    /**
     * @Inject
     * @var SystemPostService
     */
    protected $postService;

    /**
     * 获取用户列表
     * @GetMapping("getUserList")
     * @return NyuwaResponse
     */
    public function getUserList(): NyuwaResponse
    {
        return $this->success($this->userService->getPageList(nyuwa_request()->all()));
    }

    /**
     * 获取部门树列表
     * @GetMapping("getDeptTreeList")
     * @return NyuwaResponse
     */
    public function getDeptTreeList(): NyuwaResponse
    {
        return $this->success($this->deptService->getSelectTree());
    }

    /**
     * 获取角色列表
     * @GetMapping("getRoleList")
     * @return NyuwaResponse
     */
    public function getRoleList()
    {
        return $this->success($this->roleService->getList());
    }

    /**
     * 获取岗位列表
     * @GetMapping("getPostList")
     * @return NyuwaResponse
     */
    public function getPostList(): NyuwaResponse
    {
        return $this->success($this->postService->getList());
    }

}