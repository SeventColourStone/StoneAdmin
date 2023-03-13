<?php

/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**

 */

declare(strict_types=1);
namespace plugin\stone\nyuwa\generator;


use plugin\stone\app\model\setting\SettingGenerateColumns;
use plugin\stone\app\model\setting\SettingGenerateTables;
use plugin\stone\nyuwa\exception\NormalStatusException;
use plugin\stone\nyuwa\helper\Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * 控制器生成
 * Class ControllerGenerator
 * @package Mine\Generator
 */
class ControllerGenerator extends NyuwaGenerator implements CodeGenerator
{
    /**
     * @var SettingGenerateTables
     */
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
     * @return ControllerGenerator
     */
    public function setGenInfo(SettingGenerateTables $model): ControllerGenerator
    {
        $this->model = $model;
        $this->filesystem = nyuwa_app(Filesystem::class);
        if (empty($model->module_name) || empty($model->menu_name)) {
            throw new NormalStatusException(nyuwa_trans('setting.gen_code_edit'));
        }
        $this->setNamespace($this->model->namespace);
        $this->setPackage(env("GENCODE_PACKAGE","business"));
        return $this->placeholderReplace();
    }

    /**
     * 生成代码
     */
    public function generator($genFilePath = null): void
    {
        $module = Str::camel($this->model->module_name);

        $package =  $this->getPackage();

        if ($this->model->generate_type == '0') {
            $path = BASE_PATH . "/runtime/generate/php/plugin/stone/app/controller/{$package}/";
        } else {
            $path = BASE_PATH . "/plugin/stone/app/controller/{$package}/";
        }
        if (!empty($genFilePath)){
            $path = $genFilePath."/php/plugin/stone/app/controller/{$package}/";
        }
//        if (!empty($this->model->package_name)) {
//            $path .= Str::title($this->model->package_name) . '/';
//        }
        $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);
        $this->filesystem->dumpFile($path . "{$this->getClassName()}.php", $this->replace()->getCodeContent());

//        var_dump("文件生成ControllerGenerator");
    }

    /**
     * 预览代码
     */
    public function preview(): string
    {
        return $this->replace()->getCodeContent();
    }

    /**
     * 获取生成控制器的类型
     * @return string
     */
    public function getType(): string
    {
        return ucfirst($this->model->type);
    }

    /**
     * 获取控制器模板地址
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return $this->getStubDir() . 'Controller/main.stub';
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
    protected function placeholderReplace(): ControllerGenerator
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
            '{NAMESPACE}',
            '{COMMENT}',
            '{USE}',
            '{CLASS_NAME}',
            '{SERVICE}',
            '{CONTROLLER_ROUTE}',
            '{FUNCTIONS}',
            '{VALIDATE}',//'{REQUEST}',
            '{INDEX_PERMISSION}',
            '{RECYCLE_PERMISSION}',
            '{SAVE_PERMISSION}',
            '{READ_PERMISSION}',
            '{UPDATE_PERMISSION}',
            '{DELETE_PERMISSION}',
            '{REAL_DELETE_PERMISSION}',
            '{RECOVERY_PERMISSION}',
            '{IMPORT_PERMISSION}',
            '{EXPORT_PERMISSION}',
            '{DTO_CLASS}',
            '{PK}',
            '{STATUS_VALUE}',
            '{STATUS_FIELD}',
            '{NUMBER_FIELD}',
            '{NUMBER_TYPE}',
            '{NUMBER_VALUE}',
        ];


    }

    /**
     * 获取要替换占位符的内容
     */
    protected function getReplaceContent(): array
    {
        return [
            $this->initNamespace(),
            $this->getComment(),
            $this->getUse(),
            $this->getClassName(),
            $this->getServiceName(),
            $this->getControllerRoute(),
            $this->getFunctions(),
            $this->getValidateName(),  //$this->getRequestName(),
            $this->getMethodRoute('index'),
            $this->getMethodRoute('recycle'),
            $this->getMethodRoute('save'),
            $this->getMethodRoute('read'),
            $this->getMethodRoute('update'),
            $this->getMethodRoute('delete'),
            $this->getMethodRoute('realDelete'),
            $this->getMethodRoute('recovery'),
            $this->getMethodRoute('import'),
            $this->getMethodRoute('export'),
            $this->getDtoClass(),
            $this->getPk(),
            $this->getStatusValue(),
            $this->getStatusField(),
            $this->getNumberField(),
            $this->getNumberType(),
            $this->getNumberValue(),
        ];
    }

    /**
     * 初始化控制器命名空间
     * @return string
     */
    protected function initNamespace(): string
    {
        $namespace = $this->getNamespace() . "\\controller\\".$this->getPackage();
        return $namespace;
    }

    /**
     * 获取控制器注释
     * @return string
     */
    protected function getComment(): string
    {
        return $this->model->menu_name . '控制器';
    }

    /**
     * 获取使用的类命名空间
     * @return string
     */
    protected function getUse(): string
    {
        return <<<UseNamespace
use {$this->getNamespace()}\\service\\{$this->getPackage()}\\{$this->getBusinessName()}Service;
use {$this->getNamespace()}\\validate\\{$this->getPackage()}\\{$this->getBusinessName()}Validate;
UseNamespace;
    }

    /**
     * 获取控制器类名称
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->getShortBusinessName() . 'Controller';
    }

    /**
     * 获取服务类名称
     * @return string
     */
    protected function getServiceName(): string
    {
        return $this->getBusinessName() . 'Service';
    }

    /**
     * 获取控制器路由
     * @return string
     */
    protected function getControllerRoute(): string
    {
        return sprintf(
            '%s/%s',
            Str::lower($this->model->module_name),
            $this->getShortBusinessName()
        );
    }

    /**
     * @return string
     */
    protected function getFunctions(): string
    {
        $menus = explode(',', $this->model->generate_menus);
        $otherMenu = [$this->model->type === 'single' ? 'singleList' : 'treeList'];
        if (in_array('recycle', $menus)) {
            $otherMenu[] = $this->model->type === 'single' ? 'singleRecycleList' : 'treeRecycleList';
            array_push($otherMenu, ...['realDelete', 'recovery']);
            unset($menus[array_search('recycle', $menus)]);
        }
        array_unshift($menus, ...$otherMenu);
        $phpCode = '';
        $path = $this->getStubDir() . 'Controller/';
        foreach ($menus as $menu) {
            $content = file_get_contents($path . $menu . '.stub');
            $phpCode .= $content;
        }
        return $phpCode;
    }

    /**
     * 获取方法路由
     * @param string $route
     * @return string
     */
    protected function getMethodRoute(string $route): string
    {
        return sprintf(
            '%s:%s:%s',
            Str::lower($this->model->module_name),
            $this->getShortBusinessName(),
            $route
        );
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return sprintf(
            "\%s\dto\%s\%s::class",
            $this->model->namespace,
            $this->getPackage(),
            $this->getBusinessName() . 'Dto'
        );
    }

    /**
     * @return string
     */
    protected function getPk(): string
    {
        return SettingGenerateColumns::query()
            ->where('table_id', $this->model->id)
            ->where('is_pk', '1')
            ->value('column_name');
    }

    /**
     * @return string
     */
    protected function getStatusValue(): string
    {
        return 'statusValue';
    }

    /**
     * @return string
     */
    protected function getStatusField(): string
    {
        return 'statusName';
    }

    /**
     * @return string
     */
    protected function getNumberField(): string
    {
        return 'numberName';
    }

    /**
     * @return string
     */
    protected function getNumberType(): string
    {
        return 'numberType';
    }

    /**
     * @return string
     */
    protected function getNumberValue(): string
    {
        return 'numberValue';
    }

    /**
     * 获取验证器
     * @return string
     */
    protected function getRequestName(): string
    {
        return $this->getBusinessName(). 'Request';
    }
    /**
     * 获取验证器
     * @return string
     */
    protected function getValidateName():string
    {
        return $this->getBusinessName(). 'Validate';

    }

    /**
     * 获取业务名称
     * @return string
     */
    public function getBusinessName(): string
    {
        return Str::studly(str_replace(env('DB_PREFIX'), '', $this->model->table_name));
    }

    /**
     * 获取短业务名称
     * @return string
     */
    public function getShortBusinessName(): string
    {
        return Str::studly(str_replace(
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