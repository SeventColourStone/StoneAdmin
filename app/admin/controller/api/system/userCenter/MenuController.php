<?php
declare(strict_types=1);
namespace app\admin\controller\api\system\userCenter;

use app\admin\service\system\SystemMenuService;
use app\admin\validate\system\SystemMenuValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * Class MenuController
 * @package App\System\Controller
 * @Controller(prefix="system/menu")
 * @Auth
 */
class MenuController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemMenuService
     */
    protected $service;

    /**
     * @Inject
     * @var SystemMenuValidate
     */
    protected $validate;

    /**
     * 获取菜单树
     * @GetMapping("index")
     * @Permission("system:menu:index")
     */
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getTreeList($request->all()));
    }

    /**
     * 从回收站获取菜单树
     * @GetMapping("recycle")
     * @Permission("system:menu:recycle")
     */
    public function recycle(Request $request):NyuwaResponse
    {
        return $this->success($this->service->getTreeListByRecycle($request->all()));
    }

    /**
     * 前端选择树（不需要权限）
     * @GetMapping("tree")
     */
    public function tree(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getSelectTree());
    }

    /**
     * 新增菜单
     * @PostMapping("save")
     * @param SystemMenuCreateRequest $request
     * @return NyuwaResponse
     * @Permission("system:menu:save")
     * @OperationLog
     */
    public function save(Request $request): NyuwaResponse
    {
        return $this->success(['id' => $this->service->save($request->all())]);
    }

    /**
     * 更新菜单
     * @PutMapping("update/{id}")
     * @Permission("system:menu:update")
     * @param int $id
     * @param SystemMenuCreateRequest $request
     * @return NyuwaResponse
     * @OperationLog
     */
    public function update(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("update")->check($request->all());
        $id = $data["id"];
        return $this->service->update($id, $data)
            ? $this->success() : $this->error(nyuwa_trans('mineadmin.data_no_change'));
    }

    /**
     * 单个或批量删除菜单到回收站
     * @DeleteMapping("delete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:menu:delete")
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除菜单 （清空回收站）
     * @DeleteMapping("realDelete/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:menu:realDelete")
     * @OperationLog
     */
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        $menus = $this->service->realDel($ids);
        return is_null($menus) ?
        $this->success() :
        $this->success(nyuwa_trans('system.exists_children_ctu', ['names' => implode(',', $menus)]));
    }

    /**
     * 单个或批量恢复在回收站的菜单
     * @PutMapping("recovery/{ids}")
     * @param String $ids
     * @return NyuwaResponse
     * @Permission("system:menu:recovery")
     */
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        $ids = $data["id"];
        return $this->service->recovery($ids) ? $this->success() : $this->error();
    }
}
