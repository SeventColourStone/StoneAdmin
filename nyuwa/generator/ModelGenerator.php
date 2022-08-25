<?php

/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
/**

 */

declare(strict_types=1);
namespace nyuwa\generator;

use app\admin\model\setting\SettingGenerateTables;
use app\admin\service\setting\SettingGenerateColumnsService;
use nyuwa\exception\NormalStatusException;
use nyuwa\helper\Str;
use support\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Webman\Console\Application;
use Webman\Console\Command;

/**
 * 模型生成
 * Class ModelGenerator
 * @package Mine\Generator
 */
class ModelGenerator extends NyuwaGenerator implements CodeGenerator
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
     * @return ModelGenerator
     */
    public function setGenInfo(SettingGenerateTables $model): ModelGenerator
    {
        $this->model = $model;
        $this->filesystem = nyuwa_app(Filesystem::class);
        if (empty($model->module_name) || empty($model->menu_name)) {
            throw new NormalStatusException(nyuwa_trans('setting.gen_code_edit'));
        }
        $this->setNamespace($this->model->namespace);
        return $this;
    }

    /**
     * 生成代码
     */
    public function generator(): void
    {
        $module = Str::camel($this->model->module_name);
        if ($this->model->generate_type == '0') {
            $path = BASE_PATH . "/runtime/generate/php/app/{$module}/Model/";
        } else {
            $path = BASE_PATH . "/app/{$module}/Model/";
        }
        $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);

        $command = [
            'command'  => 'mine:model-gen',
            '--module' => $this->model->module_name,
            '--table'  => $this->model->table_name
        ];

//        if (! Str::contains($this->model->table_name, Str::lower($this->model->module_name))) {
//            var_dump("测试1.".$this->model->table_name." ".Str::lower($this->model->module_name));
//            throw new NormalStatusException(nyuwa_trans('setting.gen_model_error'), 500);
//        }

        if (mb_strlen($this->model->table_name) === mb_strlen($this->model->module_name)) {
            throw new NormalStatusException(nyuwa_trans('setting.gen_model_error'), 500);
        }

        $input = new ArrayInput($command);
        $output = new NullOutput();

        $application = Container::get(Command::class);
        $application->setAutoExit(false);

        $moduleName = Str::title($this->model->module_name);
        $modelName  = Str::studly(str_replace(env('DB_PREFIX'), '', $this->model->table_name));
        var_dump($command);
        $i = $application->run($input, $output);
        if ($i === 0) {

            // 对模型文件处理
            if ($modelName[strlen($modelName) - 1] == 's' && $modelName[strlen($modelName) - 2] != 's') {
                $oldName = Str::substr($modelName, 0, (strlen($modelName) - 1));
                $oldPath = BASE_PATH . "/app/{$moduleName}/Model/{$oldName}.php";
                $sourcePath = BASE_PATH . "/app/{$moduleName}/Model/{$modelName}.php";
                $this->filesystem->dumpFile(
                    $sourcePath,
                    str_replace($oldName, $modelName, file_get_contents($oldPath))
                );
                @unlink($oldPath);
            } else {
                $sourcePath = BASE_PATH . "/app/{$moduleName}/Model/{$modelName}.php";
            }

            if (!empty($this->model->options['relations'])) {
                $this->filesystem->dumpFile(
                    $sourcePath,
                    str_replace('}', $this->getRelations() . "\n}", file_get_contents($sourcePath))
                );
            }

            // 压缩包下载
            if ($this->model->generate_type == '0') {
                $toPath = BASE_PATH . "/runtime/generate/php/app/{$moduleName}/Model/{$modelName}.php";

                $isFile = is_file($sourcePath);

                if ($isFile) {
                    $this->filesystem->copy($sourcePath, $toPath);
                } else {
                    $this->filesystem->copy($sourcePath, $toPath);
                }
            }
        } else {
            var_dump("测试3");
            throw new NormalStatusException(nyuwa_trans('setting.gen_model_error'), 500);
        }
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
    protected function placeholderReplace(): ModelGenerator
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
        return $this->getNamespace() . "\\Model";
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