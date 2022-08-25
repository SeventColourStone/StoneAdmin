<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemLoginLogService;
use app\admin\service\system\SystemOperLogService;
use app\admin\service\system\SystemQueueLogService;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaRequest;
use nyuwa\NyuwaResponse;

class LogsController extends NyuwaController
{
    /**
     * 登录日志服务
     * @var SystemLoginLogService
     * @Inject
     */
    protected SystemLoginLogService $loginLogService;

    /**
     * 操作日志服务
     * @var SystemOperLogService
     * @Inject
     */
    protected SystemOperLogService $operLogService;

//    /**
//     * 队列日志服务
//     * @var SystemQueueLogService
//     * @Inject
//     */
//    #[Inject]
//    protected SystemQueueLogService $queueLogService;

    /**
     * 获取登录日志列表
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("getLoginLogPageList"), Permission("system:loginLog")]
    public function getLoginLogPageList(NyuwaRequest $request): NyuwaResponse
    {
        return $this->success($this->loginLogService->getPageList($request->all()));
    }

    /**
     * 获取操作日志列表
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("getOperLogPageList"), Permission("system:operLog")]
    public function getOperLogPageList(NyuwaRequest $request): NyuwaResponse
    {
        return $this->success($this->operLogService->getPageList($request->all()));
    }

//    /**
//     * 获取队列日志列表
//     * @return NyuwaResponse
//     * @throws \Psr\Container\ContainerExceptionInterface
//     * @throws \Psr\Container\NotFoundExceptionInterface
//     */
//    #[GetMapping("getQueueLogPageList"), Permission("system:queueLog")]
//    public function getQueueLogPageList(): NyuwaResponse
//    {
//        return $this->success($this->queueLogService->getPageList($request->all()));
//    }

//    /**
//     * 删除队列日志
//     * @param String $ids
//     * @return NyuwaResponse
//     * @throws \Psr\Container\ContainerExceptionInterface
//     * @throws \Psr\Container\NotFoundExceptionInterface
//     */
//    #[DeleteMapping("deleteQueueLog/{ids}"), Permission("system:queueLog:delete"), OperationLog]
//    public function deleteQueueLog(String $ids): NyuwaResponse
//    {
//        return $this->queueLogService->delete($ids) ? $this->success() : $this->error();
//    }

//    /**
//     * 重新加入队列
//     * @param String $ids
//     * @return NyuwaResponse
//     * @throws \Psr\Container\ContainerExceptionInterface
//     * @throws \Psr\Container\NotFoundExceptionInterface
//     */
//    #[PutMapping("updateQueueLog/{ids}"), Permission("system:queueLog:produceStatus"), OperationLog]
//    public function updateQueueLog(String $ids): NyuwaResponse
//    {
//        return $this->queueLogService->updateProduceStatus($ids) ? $this->success() : $this->error();
//    }

    /**
     * 删除操作日志
     * @param String $ids
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[DeleteMapping("deleteOperLog/{ids}"), Permission("system:operLog:delete"), OperationLog]
    public function deleteOperLog(NyuwaRequest $request,String $ids): NyuwaResponse
    {
        return $this->operLogService->delete($ids) ? $this->success() : $this->error();
    }

    /**
     * 删除登录日志
     * @param String $ids
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[DeleteMapping("deleteLoginLog/{ids}"), Permission("system:loginLog:delete"), OperationLog]
    public function deleteLoginLog(NyuwaRequest $request,String $ids): NyuwaResponse
    {
        
        return $this->loginLogService->delete($ids) ? $this->success() : $this->error();
    }

}