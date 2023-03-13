<?php


namespace plugin\stone\app\service\setting;


use DI\Attribute\Inject;
use Illuminate\Support\Collection;
use plugin\stone\nyuwa\abstracts\AbstractService;
use plugin\stone\nyuwa\generator\ModuleGenerator;
use plugin\stone\nyuwa\helper\Str;
use plugin\stone\nyuwa\Nyuwa;
use support\Redis;

class ModuleService extends AbstractService
{

    /**
     * @var Nyuwa
     */
    #[Inject]
    protected Nyuwa $nyuwa;


    /**
     * 获取表状态分页列表
     * @param array|null $params
     * @param bool $isScope
     * @return array
     */
    public function getPageList(?array $params = [], bool $isScope = true): array
    {
        return $this->getArrayToPageList($params);
    }

    /**
     * 数组数据搜索器
     * @param Collection $collect
     * @param array $params
     * @return Collection
     */
    protected function handleArraySearch(Collection $collect, array $params): Collection
    {
        if ($params['name'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row['name'], $params['name']);
            });
        }

        if ($params['label'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row['label'], $params['label']);
            });
        }
        return $collect;
    }

    /**
     * 设置需要分页的数组数据
     * @param array $params
     * @return array
     */
    protected function getArrayData(array $params = []): array
    {
        return $this->getModuleCache();
    }

    /**
     * 创建模块
     * @param array $moduleInfo
     * @return bool
     */
    public function createModule(array $moduleInfo): bool
    {
        /** @var ModuleGenerator $moduleGen */
        $moduleGen = nyuwa_app(ModuleGenerator::class);
        $moduleGen->setModuleInfo($moduleInfo)->createModule();
        $this->setModuleCache();
        return true;
    }

    /**
     * 执行模块安装
     * @param string $name
     * @return bool
     */
    public function installModuleData(string $name): bool
    {
        try {
            $migrateCommand = [ 'command' => 'nyuwa:migrate-run', 'name' => $name ];
            $seedCommand = [ 'command' => 'nyuwa:seeder-run', 'name' => $name ];
            $application = container()->get(\Hyperf\Contract\ApplicationInterface::class);
            $application->setAutoExit(false);
            $application->run(new ArrayInput($migrateCommand), new NullOutput());
            $application->run(new ArrayInput($seedCommand), new NullOutput());
            $this->setModuleCache();
            return true;
        } catch (\Throwable $e) {
            console()->error($e->getMessage());
            return false;
        }
    }

    /**
     * 卸载模块
     * @param string $name
     * @return bool
     * @throws \Throwable
     */
    public function uninstallModule(string $name): bool
    {
        try {
            $migrate = container()->get(\Hyperf\Database\Migrations\Migrator::class);
            $path = BASE_PATH . '/app/' . $name . '/Database/Migrations';
            $migrate->rollback([$path]);
            is_dir($path . '/Update') && $migrate->rollback([$path . '/Update']);
            $this->deleteModule($name);
            $this->setModuleCache();
            return true;
        } catch (\Throwable $e) {
            console()->error($e->getMessage());
            return false;
        }
    }

    /**
     * 删除模块
     * @param string $name
     * @return bool
     */
    public function deleteModule(string $name): bool
    {
        /** @var Filesystem $filesystem */
        $filesystem = make(Filesystem::class);
        $modulePath = BASE_PATH . '/app/' . ucfirst($name);
        return $filesystem->deleteDirectory($modulePath);
    }

    /**
     * 缓存模块信息
     * @param string|null $moduleName 模块名
     * @param array $data 模块数据
     */
    public function setModuleCache(?string $moduleName = null, array $data = []): void
    {
        $key = 'modules';
        $this->nyuwa->scanModule();
        $modules = $this->nyuwa->getModuleInfo();
        if (! empty($moduleName)) {
            $modules[$moduleName] = $data;
        }
        Redis::set($key, serialize($modules));
    }

    /**
     * 获取模块缓存信息
     * @param string|null $moduleName
     * @return array
     */
    public function getModuleCache(?string $moduleName = null): array
    {
        $key = 'modules';
        if (Redis::exists($key)) {
            $data = unserialize(Redis::get($key));
            return !empty($moduleName) && isset($data[$moduleName]) ? $data[$moduleName] : $data;
        } else {
            $this->setModuleCache();
            $this->nyuwa->scanModule();
            return $this->nyuwa->getModuleInfo();
        }
    }

    /**
     * 启停用模块
     * @param array $data
     * @return bool
     */
    public function modifyStatus(array $data): bool
    {
        $modules = $this->nyuwa->getModuleInfo();
        if (isset($modules[$data['name']])) {

            $filePath = BASE_PATH . '/plugin/stone/' . $data['name'] . '/config.json';
            $status = $data['status'] ? 'true' : 'false';
            $content = preg_replace(
                '/\"enabled\":\s(true|false),/',
                '"enabled": ' . $status . ',',
                file_get_contents($filePath)
            );
            $result = (bool) file_put_contents($filePath, $content);
            $this->setModuleCache();
            return $result;
        } else {
            return false;
        }
    }

}