<?php

declare(strict_types=1);
namespace app\admin\controller\api\system\userCenter;

use app\admin\service\system\SystemPostService;
use app\admin\validate\system\SystemPostValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * Class PostController
 * @package App\System\Controller
 * @Controller(prefix="system/post")
 * @Auth
 */
class PostController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemPostService
     */
    protected $service;
    /**
     * @Inject
     * @var SystemPostValidate
     */
    protected $validate;

    /**
     * 获取列表分页数据
     * @GetMapping("index")
     * @return NyuwaResponse
     * @Permission("system:post:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * @GetMapping("recycle")
     * @return NyuwaResponse
     * @Permission("system:post:recycle")
     */
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($this->request->all()));
    }

    /**
     * 获取列表数据
     * @GetMapping("list")
     * @return NyuwaResponse
     */
    public function list(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getList());
    }

    /**
     * 保存数据
     * @PostMapping("save")
     * @param SystemPostCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:post:save")
     * @OperationLog
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 获取一条数据信息
     * @GetMapping("read/{id}")
     * @param int $id
     * @return NyuwaResponse
     * @Permission("system:post:read")
     */
    public function read(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $id = $data["id"];
        return $this->success($this->service->read($id));
    }

    /**
     * 更新数据
     * @PutMapping("update/{id}")
     * @param int $id
     * @param SystemPostCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:post:update")
     */
    public function update(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $data) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:post:delete")
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除数据 （清空回收站）
     * @DeleteMapping("realDelete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:post:realDelete")
     * @OperationLog
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->realDelete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的数据
     * @PutMapping("recovery/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:post:recovery")
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }

    /**
     * 更改岗位状态
     * @PutMapping("changeStatus")
     * @param SystemPostStatusRequest $request
     * @return NyuwaResponse
     * @Permission("system:post:changeStatus")
     * @OperationLog
     */
    public function changeStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("status")->check($request->all());
        return $this->service->changeStatus( $data['id'], (string) $data['status'])
            ? $this->success() : $this->error();
    }
}
