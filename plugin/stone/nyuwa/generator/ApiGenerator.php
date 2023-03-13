<?php
/** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
/**

 */

declare(strict_types=1);
namespace plugin\stone\nyuwa\generator;

use DI\Attribute\Inject;
use plugin\stone\app\model\setting\SettingGenerateTables;
use plugin\stone\nyuwa\exception\NormalStatusException;
use plugin\stone\nyuwa\helper\Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * JS API文件生成
 * Class ApiGenerator
 * @package Mine\Generator
 */
class ApiGenerator extends NyuwaGenerator implements CodeGenerator
{
    /**
     * @var SettingGenerateTables
     */
    #[Inject]
    protected SettingGenerateTables $model;

    /**
     * @var string
     */
    protected string $codeContent;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * 设置生成信息
     * @param SettingGenerateTables $model
     * @return ApiGenerator
     */
    public function setGenInfo(SettingGenerateTables $model): ApiGenerator
    {
        $this->model = $model;
        $this->filesystem = nyuwa_app(Filesystem::class);
        if (empty($model->module_name) || empty($model->menu_name)) {
            throw new NormalStatusException(nyuwa_trans('setting.gen_code_edit'));
        }
        $this->setPackage(env("GENCODE_PACKAGE","business"));
        return $this->placeholderReplace();
    }

    /**
     * 生成代码
     */
    public function generator($genFilePath = null): void
    {
        $filename = Str::camel(str_replace(env('DB_PREFIX'), '', $this->model->table_name));
        $module = Str::lower($this->model->module_name);
        $package = $this->getPackage();

//        $this->filesystem->mkdir(BASE_PATH . "/runtime/generate/vue/src/api/apis/{$package}", 0755);
        $path = BASE_PATH . "/runtime/generate/vue/src/api/apis/{$package}/{$filename}.js";
        if (!empty($genFilePath)){
            $path = $genFilePath."/vue/src/api/apis/{$package}/{$filename}.js";
        }
        $this->filesystem->dumpFile($path, $this->replace()->getCodeContent());

//        var_dump("文件生成ApiGenerator");
    }

    /**
     * 预览代码
     */
    public function preview(): string
    {
        return $this->replace()->getCodeContent();
    }

    /**
     * 获取模板地址
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return $this->getStubDir().'/Api/main.stub';
    }

    /**
     * 读取模板内容
     * @return string
     */
    protected function readTemplate(): string
    {
        return file_get_contents($this->getTemplatePath());
    }

    /**
     * 占位符替换
     */
    protected function placeholderReplace(): ApiGenerator
    {
        $this->setCodeContent(str_replace(
            $this->getPlaceHolderContent(),
            $this->getReplaceContent(),
            $this->readTemplate()
        ));

        return $this;
    }

    /**
     * 获取要替换的占位符
     */
    protected function getPlaceHolderContent(): array
    {
        return [
            '{LOAD_API}',
            '{COMMENT}',
            '{BUSINESS_NAME}',
            '{REQUEST_ROUTE}',
        ];
    }

    /**
     * 获取要替换占位符的内容
     */
    protected function getReplaceContent(): array
    {
        return [
            $this->getLoadApi(),
            $this->getComment(),
            $this->getBusinessName(),
            $this->getRequestRoute(),
        ];
    }

    protected function getLoadApi(): string
    {
        $menus = explode(',', $this->model->generate_menus);
        $ignoreMenus = ['realDelete', 'recovery'];

        array_unshift($menus, $this->model->type === 'single' ? 'singleList' : 'treeList');

        foreach ($ignoreMenus as $menu) {
            if (in_array($menu, $menus)) {
                unset($menus[array_search($menu, $menus)]);
            }
        }

        $jsCode = '';
        $path = $this->getStubDir() . '/Api/';
        foreach ($menus as $menu) {
            $content = file_get_contents($path . $menu . '.stub');
            $jsCode .= $content;
        }

        return $jsCode;
    }

    /**
     * 获取控制器注释
     * @return string
     */
    protected function getComment(): string
    {
        return $this->getBusinessName(). ' API JS';
    }

    /**
     * 获取请求路由
     * @return string
     */
    protected function getRequestRoute(): string
    {
        return Str::lower($this->getPackage()) . '/' . $this->getShortBusinessName();
    }

    /**
     * 获取业务名称
     * @return string
     */
    protected function getBusinessName(): string
    {
        return $this->model->menu_name;
    }

    /**
     * 获取短业务名称
     * @return string
     */
    public function getShortBusinessName(): string
    {
        return Str::camel(str_replace(
            Str::lower($this->model->module_name),
            '',
            str_replace(env('DB_PREFIX'), '', $this->model->table_name)
        ));
    }

    /**
     * 设置代码内容
     * @param string $content
     */
    public function setCodeContent(string $content)
    {
        $this->codeContent = $content;
    }

    /**
     * 获取代码内容
     * @return string
     */
    public function getCodeContent(): string
    {
        return $this->codeContent;
    }

}