<?php
/** @noinspection PhpIllegalStringOffsetInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
/**

 */

declare(strict_types=1);
namespace plugin\stone\nyuwa\generator;


use plugin\stone\app\model\setting\SettingGenerateColumns;
use plugin\stone\app\model\setting\SettingGenerateTables;
use plugin\stone\app\service\setting\SettingGenerateColumnsService;
use plugin\stone\nyuwa\exception\NormalStatusException;
use plugin\stone\nyuwa\generator\traits\MapperGeneratorTraits;
use plugin\stone\nyuwa\helper\Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Mapper类生成
 * Class MapperGenerator
 * @package Mine\Generator
 */
class MapperGenerator extends NyuwaGenerator implements CodeGenerator
{
    use MapperGeneratorTraits;

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
     * @return MapperGenerator
     */
    public function setGenInfo(SettingGenerateTables $model): MapperGenerator
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
        $package = $this->getPackage();
        if ($this->model->generate_type == '0') {
            $path = BASE_PATH . "/runtime/generate/php/app/{$package}/mapper/";
        } else {
            $path = BASE_PATH . "/app/{$package}/mapper/";
        }
        if (!empty($genFilePath)){
            $path = $genFilePath."/php/plugin/stone/app/mapper/{$package}/";//"/php/app/{$module}/mapper/";
        }
        $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);
        $this->filesystem->dumpFile($path . "{$this->getClassName()}.php", $this->replace()->getCodeContent());

//        var_dump("文件生成MapperGenerator");
    }

    /**
     * 预览代码
     */
    public function preview(): string
    {
        return $this->replace()->getCodeContent();
    }

    /**
     * 获取生成的类型
     * @return string
     */
    public function getType(): string
    {
        return ucfirst($this->model->type);
    }

    /**
     * 获取模板地址
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return $this->getStubDir().$this->getType().'/mapper.stub';
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
    protected function placeholderReplace(): MapperGenerator
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
            '{USE}',
            '{COMMENT}',
            '{CLASS_NAME}',
            '{MODEL}',
            '{FIELD_ID}',
            '{FIELD_PID}',
            '{FIELD_NAME}',
            '{SEARCH}'
        ];
    }

    /**
     * 获取要替换占位符的内容
     */
    protected function getReplaceContent(): array
    {
        return [
            $this->initNamespace(),
            $this->getUse(),
            $this->getComment(),
            $this->getClassName(),
            $this->getModelName(),
            $this->getFieldIdName(),
            $this->getFieldPidName(),
            $this->getFieldName(),
            $this->getSearch()
        ];
    }

    /**
     * 初始化服务类命名空间
     * @return string
     */
    protected function initNamespace(): string
    {
        return $this->getNamespace() . "\\mapper\\".$this->getPackage();
    }

    /**
     * 获取控制器注释
     * @return string
     */
    protected function getComment(): string
    {
        return $this->model->menu_name. 'Mapper类';
    }

    /**
     * 获取使用的类命名空间
     * @return string
     */
    protected function getUse(): string
    {
        return <<<UseNamespace
use {$this->getNamespace()}\\model\\{$this->getPackage()}\\{$this->getBusinessName()};
UseNamespace;
    }

    /**
     * 获取类名称
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->getBusinessName().'Mapper';
    }

    /**
     * 获取Model类名称
     * @return string
     */
    protected function getModelName(): string
    {
        return $this->getBusinessName();
    }

    /**
     * 获取树表ID
     * @return string
     */
    protected function getFieldIdName(): string
    {
        if ($this->getType() == 'Tree') {
            if ( $this->model->options['tree_id'] ?? false ) {
                return $this->model->options['tree_id'];
            } else {
                return 'id';
            }
        }
        return '';
    }

    /**
     * 获取树表父ID
     * @return string
     */
    protected function getFieldPidName(): string
    {
        if ($this->getType() == 'Tree') {
            if ( $this->model->options['tree_pid'] ?? false ) {
                return $this->model->options['tree_pid'];
            } else {
                return 'parent_id';
            }
        }
        return '';
    }

    /**
     * 获取树表显示名称
     * @return string
     */
    protected function getFieldName(): string
    {
        if ($this->getType() == 'Tree') {
            if ( $this->model->options['tree_name'] ?? false ) {
                return $this->model->options['tree_name'];
            } else {
                return 'name';
            }
        }
        return '';
    }

    /**
     * 获取搜索内容
     * @return string
     */
    protected function getSearch(): string
    {
        /* @var SettingGenerateColumns $model */
        $model = nyuwa_app(SettingGenerateColumnsService::class)->mapper->getModel();
        $columns = $model->newQuery()
            ->where('table_id', $this->model->id)
            ->where('is_query', '1')
            ->get(['column_name', 'column_comment', 'query_type'])->toArray();

        $phpContent = '';
        foreach ($columns as $column) {
            $phpContent .= $this->getSearchCode($column);
        }

        return $phpContent;
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