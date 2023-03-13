<?php

/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
/**

 */

declare(strict_types=1);
namespace plugin\stone\nyuwa\generator;

use plugin\stone\app\model\setting\SettingGenerateTables;
use plugin\stone\app\service\setting\SettingGenerateColumnsService;
use plugin\stone\nyuwa\exception\NormalStatusException;
use plugin\stone\nyuwa\helper\Str;
use support\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Webman\Console\Application;
use Webman\Console\Command;

/**
 * 模型生成，不调用command
 * Class ModelGenerator
 * @package Mine\Generator
 */
class ModelGenerate extends NyuwaGenerator implements CodeGenerator
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
     * @return ModelGenerate
     */
    public function setGenInfo(SettingGenerateTables $model): ModelGenerate
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
            $path = BASE_PATH . "/runtime/generate/php/app/{$package}/model/";
        } else {
            $path = BASE_PATH . "/app/{$package}/model/";
        }

        if (!empty($genFilePath)){
            $path = $genFilePath."/php/plugin/stone/app/model/{$package}/";
        }

        $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);
        $this->filesystem->dumpFile($path . "{$this->getClassName()}.php", $this->replace()->getCodeContent());

//        var_dump("文件生成ModelGenerate");
    }

    /**
     * 预览代码
     */
    public function preview(): string
    {
        return $this->placeholderReplace()->getCodeContent();
    }

    /**
     * 获取控制器模板地址
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return $this->getStubDir().'model.stub';
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
    protected function placeholderReplace(): ModelGenerate
    {
        $this->setCodeContent(str_replace(
            $this->getPlaceHolderContent(),
            $this->getReplaceContent(),
            $this->readTemplate(),
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
            '{CLASS_NAME}',
            '{TABLE_NAME}',
            '{FILL_ABLE}',
            '{RELATIONS}',
        ];
    }

    /**
     * 获取要替换占位符的内容
     */
    protected function getReplaceContent(): array
    {
        return [
            $this->initNamespace(),
            $this->getClassName(),
            $this->getTableName(),
            $this->getFillAble(),
            $this->getRelations(),
        ];
    }

    /**
     * 初始化模型命名空间
     * @return string
     */
    protected function initNamespace(): string
    {
        return $this->getNamespace() . "\\model\\".$this->getPackage();
    }

    /**
     * 获取类名称
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->getBusinessName();
    }

    /**
     * 获取表名称
     * @return string
     */
    protected function getTableName(): string
    {
        return $this->model->table_name;
    }

    /**
     * 获取file able
     */
    protected function getFillAble(): string
    {
        $data = nyuwa_app(SettingGenerateColumnsService::class)->getList(
            ['select' => 'column_name', 'table_id' => $this->model->id]
        );
        $columns = [];
        foreach ($data as $column) {
            $columns[] = "'".$column['column_name']."'";
        }

        return implode(', ', $columns);
    }

    /**
     * @return string
     */
    protected function getRelations(): string
    {
        $prefix = env('DB_PREFIX');
        if (!empty($this->model->options['relations'])) {
            $path = $this->getStubDir() . 'ModelRelation/';
            $phpCode = '';
            foreach ($this->model->options['relations'] as $relation) {
                $content = file_get_contents($path . $relation['type'] . '.stub');
                $content = str_replace(
                    [ '{RELATION_NAME}', '{MODEL_NAME}', '{TABLE_NAME}', '{FOREIGN_KEY}', '{LOCAL_KEY}' ],
                    [ $relation['name'], $relation['model'], str_replace($prefix, '', $relation['table']), $relation['foreignKey'], $relation['localKey'] ],
                    $content
                );
                $phpCode .= $content;
            }
            return $phpCode;
        }
        return '';
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