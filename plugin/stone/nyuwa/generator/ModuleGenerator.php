<?php
/**

 */

declare(strict_types=1);

namespace plugin\stone\nyuwa\generator;


use plugin\stone\nyuwa\helper\Str;
use plugin\stone\nyuwa\Nyuwa;
use Symfony\Component\Filesystem\Filesystem;

class ModuleGenerator extends NyuwaGenerator
{
    /**
     * @var array
     */
    protected array $moduleInfo;

    /**
     * 设置模块信息
     * @param array $moduleInfo
     * @return $this
     */
    public function setModuleInfo(array $moduleInfo): ModuleGenerator
    {
        $this->moduleInfo = $moduleInfo;
        return $this;
    }

    /**
     * 生成模块基础架构
     */
    public function createModule(): bool
    {
        if (! ($this->moduleInfo['name'] ?? false)) {
            throw new \RuntimeException('模块名称为空');
        }

        $this->moduleInfo['name'] = Str::camel($this->moduleInfo['name']);

        $mine = new Nyuwa();
        $mine->scanModule();

        if (! empty($mine->getModuleInfo($this->moduleInfo['name']))) {
            throw new \RuntimeException('同名模块已存在');
        }

        $appPath = BASE_PATH . '/app/';
        $modulePath = $appPath . $this->moduleInfo['name'] . '/';

        /** @var Filesystem $filesystem */
        $filesystem = nyuwa_app(Filesystem::class);
        $filesystem->mkdir($appPath . $this->moduleInfo['name']);

        foreach ($this->getGeneratorDirs() as $dir) {
            $filesystem->mkdir($modulePath . $dir);
        }

        $this->createConfigJson($filesystem);

        return true;
    }

    /**
     * 创建模块JSON文件
     */
    protected function createConfigJson(Filesystem $filesystem)
    {
        $json = file_get_contents($this->getStubDir() . 'config.stub');

        $content = str_replace(
            ['{NAME}','{LABEL}','{DESCRIPTION}', '{VERSION}'],
            [
                $this->moduleInfo['name'],
                $this->moduleInfo['label'],
                $this->moduleInfo['description'],
                $this->moduleInfo['version']
            ],
            $json
        );

        $filesystem->dumpFile(BASE_PATH . '/app/' .$this->moduleInfo['name'] . '/config.json', $content);
    }

    /**
     * 生成的目录列表
     */
    protected function getGeneratorDirs(): array
    {
        return [
            'controller',
            'model',
            'validate',
            'service',
            'mapper',
            'dto',
        ];
    }
}