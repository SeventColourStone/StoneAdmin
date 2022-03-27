<?php


namespace app\admin\controller\api\system\setting;


use app\admin\service\setting\SettingConfigService;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

/**
 * 系统配置控制器
 */
//#[Controller(prefix: "setting/config")]
class SystemConfigController extends NyuwaController
{
    /**
     * @Inject
     * @var SettingConfigService
     */
    protected  $service;

    /**
     * 获取系统组配置 （不验证身份和权限）
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[GetMapping("getSysConfig")]
    public function getSysConfig(): NyuwaResponse
    {
        return $this->success($this->service->getSystemGroupConfig());
    }

    /**
     * 获取系统组配置
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[GetMapping("getSystemConfig"), Permission("setting:config:getSystemConfig")]
    public function getSystemGroupConfig(): NyuwaResponse
    {
        return $this->success($this->service->getSystemGroupConfig());
    }

    /**
     * 获取扩展组配置
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[GetMapping("getExtendConfig"), Permission("setting:config:getExtendConfig")]
    public function getExtendGroupConfig(): NyuwaResponse
    {
        return $this->success($this->service->getExtendGroupConfig());
    }

    /**
     * 按组名获取配置
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[GetMapping("getConfigByGroup"), Auth]
    public function getConfigByGroup(): NyuwaResponse
    {
        return $this->success($this->service->getConfigByGroup($this->request->input('groupName', '')));
    }

    /**
     * 按key获取配置
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[PostMapping("getConfigByKey")]
    public function getConfigByKey(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getConfigByKey($request->input('key', '')));
    }

    /**
     * 保存系统配置组数据
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[PostMapping("saveSystemConfig"), Permission("setting:config:saveSystemConfig"), OperationLog]
    public function saveSystemConfig(Request $request): NyuwaResponse
    {
        return $this->service->saveSystemConfig($request->all()) ? $this->success() : $this->error();
    }

    /**
     * 保存配置
     * @param SettingConfigCreateRequest $request
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[PostMapping("save"), Permission("setting:config:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        return $this->service->save($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 更新配置
     * @param SettingConfigCreateRequest $request
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[PostMapping("update"), Permission("setting:config:update"), OperationLog]
    public function update(Request $request): NyuwaResponse
    {
        return $this->service->updated($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 删除配置
     * @param string $key
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[DeleteMapping("delete/{key}"), Permission("setting:config:delete"), OperationLog]
    public function delete(Request $request,string $key): NyuwaResponse
    {
        return $this->service->delete($key) ? $this->success() : $this->error();
    }

    /**
     * 清除配置缓存
     * @return NyuwaResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    #[PostMapping("clearCache"), Permission("setting:config:clearCache"), OperationLog]
    public function clearCache(Request $request): NyuwaResponse
    {
        return $this->service->clearCache() ? $this->success() : $this->error();
    }

}
