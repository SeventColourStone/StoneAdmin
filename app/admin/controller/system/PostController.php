<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemPostService;
use app\admin\validate\system\SystemPostValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class PostController extends NyuwaController
{
    /**
     * SystemPostService 服务
     * @Inject
     * @var SystemPostService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemPostValidate 验证器
     */
    protected $validate;



    /**
     * 岗位分页列表
     */
    #[GetMapping("index"), Permission("system:user:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 岗位回收站分页列表
     */
    #[GetMapping("recycle"), Permission("system:user:recycle")]
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 获取岗位列表
     */
    #[GetMapping("list")]
    public function list(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getList());
    }

    /**
     * 保存数据
     */
    #[PostMapping("save"), Permission("system:post:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($request->all())]);
    }

    /**
     * 获取一条数据信息
     */
    #[GetMapping("read/{id}"), Permission("system:post:read")]
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->success($this->service->read($data["id"]));
    }

    /**
     * 更新数据
     */
    #[PutMapping("update/{id}"), Permission("system:post:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("update")->check($request->all());
        $data = $request->all();
        return $this->service->update($data["id"], $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站
     */
    #[DeleteMapping("delete/{ids}"), Permission("system:post:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除数据 （清空回收站）
     */
    #[DeleteMapping("realDelete/{ids}"), Permission("system:post:realDelete"), OperationLog]
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的数据
     */
    #[PutMapping("recovery/{ids}"), Permission("system:post:recovery")]
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 更改岗位状态
     */
    #[PutMapping("changeStatus"), Permission("system:post:changeStatus"), OperationLog]
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus( $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }


}