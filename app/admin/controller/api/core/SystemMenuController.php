<?php

declare(strict_types=1);

namespace app\admin\controller\api\core;


use app\admin\service\core\SystemMenuService;
use app\admin\validate\core\SystemMenuValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;


/**
 * 菜单表
 * Class SystemMenuController
 * @package app\admin\controller\api\core
 */
class SystemMenuController extends NyuwaController
{
    /**
     * SystemMenuService 服务
     * @Inject
     * @var SystemMenuService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemMenuValidate 验证器
     */
    protected $validate;

    /**
     * 列表不分页
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getList($request->all()));
    }

    /**
     * 回收站列表
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getListByRecycle($request->all()));
    }

    /**
     * 树形列表
     */
    public function treeIndex(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getTreeList($request->all()));
    }

    /**
     * 树形回收站列表
     */
    public function treeRecycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getTreeListByRecycle($request->all()));
    }


    /**
     * 前端选择树（不需要权限）
     */
    public function tree(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getSelectTree());
    }

    /**
     * 新增
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 读取一条记录
     */
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->read($data["id"]));
    }

    /**
     * 更新一条记录
     */
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量回收一条记录
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除不回收记录
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的记录
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 更改用户状态
     */
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus( $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }

}
