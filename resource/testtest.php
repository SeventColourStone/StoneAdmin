<?php


namespace 控制器命名空间;


use 控制器命名空间TestInfoService;
use 验证器命名空间TestInfoValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * 测试信息表
 * Class TestInfoController
 * @package 控制器命名空间
 */
class TestInfoController extends NyuwaController
{
    /**
     * TestInfoService 服务
     * @Inject
     * @var TestInfoService
     */
    protected $service;


    /**
     * @Inject
     * @var TestInfoValidate 验证器
     */
    protected $validate;

    /**
     * 列表
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 回收站列表
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getListByRecycle($request->all()));
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
    public function read(Request $request,int $id): NyuwaResponse
    {
        return $this->success($this->service->read($id));
    }

    /**
     * 更新一条记录
     */
    public function update(Request $request,int $id): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量回收一条记录
     */
    public function delete(Request $request,String $ids): NyuwaResponse
    {
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除不回收记录
     */
    public function realDelete(Request $request,String $ids): NyuwaResponse
    {
        return $this->service->realDelete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的记录
     */
    public function recovery(Request $request,String $ids): NyuwaResponse
    {
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

}
