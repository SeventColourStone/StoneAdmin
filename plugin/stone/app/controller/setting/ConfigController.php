<?php


namespace plugin\stone\app\controller\setting;


use plugin\stone\app\service\setting\SettingConfigService;
use plugin\stone\app\validate\setting\SettingConfigValidate;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

class ConfigController extends NyuwaController
{

    /**
     * 不需要登录的方法
     * @var string[]
     */
    protected array $noNeedLogin = ['getSysConfig','getConfigByKey', 'getSystemConfig', 'getExtendConfig'];

    /**
     * 不需要鉴权的方法
     * @var string[]
     */
    protected array $noNeedAuth = [];

    /**
     * @var SettingConfigService
     */
    #[Inject]
    protected SettingConfigService $service;


    /**
     * @var SettingConfigValidate 验证器
     */
    #[Inject]
    protected SettingConfigValidate $validate;


    /**
     * 获取系统组配置 （不验证身份和权限）
     */
    public function getSysConfig(): NyuwaResponse
    {
        return $this->success($this->service->getSystemGroupConfig());
    }

    /**
     * 获取系统组配置
     */
    #[GetMapping("getSystemConfig"), Auth, Permission("setting:config:getSystemConfig")]
    public function getSystemConfig(): NyuwaResponse
    {
        return $this->success($this->service->getSystemGroupConfig());
    }

    /**
     * 获取扩展组配置
     */
    #[GetMapping("getExtendConfig"), Auth, Permission("setting:config:getExtendConfig")]
    public function getExtendConfig(): NyuwaResponse
    {
        return $this->success($this->service->getExtendGroupConfig());
    }

    /**
     * 按组名获取配置
     */
    public function getConfigByGroup(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getConfigByGroup($request->input('groupName', '')));
    }

    /**
     * 按key获取配置
     */
    public function getConfigByKey(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getConfigByKey($request->input('key', '')));
    }

    /**
     * 保存系统配置组数据
     */
    public function saveSystemConfig(Request $request): NyuwaResponse
    {
        return $this->service->saveSystemConfig($request->all()) ? $this->success() : $this->error();
    }

    /**
     * 保存配置
     */
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("create")->check($request->all());
        return $this->success(['id' => $this->service->save($data)]);
    }

    /**
     * 更新配置
     */
    public function update(Request $request): NyuwaResponse
    {
        $this->validate->scene("updated")->check($request->all());
        $data = $request->all();
        return $this->service->updated($data) ? $this->success() : $this->error();
    }

    /**
     * 删除配置
     */
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 清除配置缓存
     */
    public function clearCache(): NyuwaResponse
    {
        return $this->service->clearCache() ? $this->success() : $this->error();
    }

}