<?php
/** @noinspection PhpExpressionResultUnusedInspection */
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
 * 验证器生成
 * Class RequestGenerator
 * @package Mine\Generator
 */
class ValidateGenerator extends NyuwaGenerator implements CodeGenerator
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
     * @var array
     */
    protected array $columns;

    /**
     * 设置生成信息
     * @param SettingGenerateTables $model
     * @return ValidateGenerator
     */
    public function setGenInfo(SettingGenerateTables $model): ValidateGenerator
    {
        $this->model = $model;
        $this->filesystem = nyuwa_app(Filesystem::class);
        if (empty($model->module_name) || empty($model->menu_name)) {
            throw new NormalStatusException(nyuwa_trans('setting.gen_code_edit'));
        }
        $this->setNamespace($this->model->namespace);
        $this->setPackage(env("GENCODE_PACKAGE","business"));
        $this->columns = SettingGenerateColumns::query()
            ->where('table_id', $model->id)
            ->where('is_insert', '1')
            ->orWhere('is_edit', '1')
            ->where('is_required', '1')
            ->orderByDesc('sort')
            ->get([ 'column_name', 'column_comment', 'is_insert', 'is_edit' ])->toArray();

        return $this->placeholderReplace();
    }

    /**
     * 生成代码
     */
    public function generator($genFilePath = null): void
    {
        $module = Str::title($this->model->module_name);
        $package = $this->getPackage();
        if ($this->model->generate_type == '0') {
            $path = BASE_PATH . "/runtime/generate/php/app/{$package}/validate/";
        } else {
            $path = BASE_PATH . "/app/{$package}/validate/";
        }

        if (!empty($genFilePath)){
            $path = $genFilePath."/php/plugin/stone/app/validate/{$package}/";
        }

        $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);
        $this->filesystem->dumpFile($path . "{$this->getClassName()}.php", $this->replace()->getCodeContent());

//        var_dump("文件生成ValidateGenerator");
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
        return $this->getStubDir() . '/Validate/main.stub';
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
    protected function placeholderReplace(): ValidateGenerator
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
            '{CLASS_NAME}',
            '{RULES}',
            '{ATTRIBUTES}',
            '{CUSTOM_RULE}',
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
            $this->getClassName(),
            $this->getRules(),
            $this->getAttributes(),
            $this->getCustomRule(),
        ];
    }

    /**
     * 初始化命名空间
     * @return string
     */
    protected function initNamespace(): string
    {
        return $this->getNamespace() . "\\validate\\".$this->getPackage();
    }

    /**
     * 获取注释
     * @return string
     */
    protected function getComment(): string
    {
        return $this->model->menu_name . '验证数据类';
    }

    /**
     * 获取类名称
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->getBusinessName() . 'Validate';
    }

    /**
     * 获取验证数据规则
     * @return string
     */
    protected function getRules(): string
    {
        $phpContent = '';
        $createCode = '';
        $updateCode = '"id",';
        $path = $this->getStubDir() . '/Validate/rule.stub';

        foreach ($this->columns as $column) {
            if ($column['is_insert'] === '1') {
                $createCode .= $this->getRuleCode($column);
            }
            if ($column['is_edit'] === '1') {
                $updateCode .= $this->getRuleCode($column);
            }
        }

        $phpContent .= str_replace(
            ['{METHOD_COMMENT}', '{METHOD_NAME}', '{LIST}'],
            ['新增数据验证规则', 'create', $createCode],
            file_get_contents($path)
        );
        $phpContent .= str_replace(
            ['{METHOD_COMMENT}', '{METHOD_NAME}', '{LIST}'],
            ['更新数据验证规则', 'update', $updateCode],
            file_get_contents($path)
        );

        return $phpContent;
    }

    /**
     * @param array $column
     * @return string
     */
    protected function getRuleCode(array &$column): string
    {
        return sprintf(
            "'%s' ,",
            $column['column_name']
        );
    }

    /**
     * @param array $column
     * @return string
     */
    protected function getCustomRuleCode(array &$column): string
    {
        $space = '      ';
        return sprintf(
            "    %s//%s 验证\n    %s'%s' => 'required',\n    ",
            $space,  $column['column_comment'],
            $space, $column['column_name']
        );
    }

    private function getCustomRule()
    {
        $customCode = "";
        foreach ($this->columns as $column) {
            $customCode .= $this->getCustomRuleCode($column);
        }
        return $customCode;
    }

    /**
     * @return string
     */
    protected function getAttributes(): string
    {
        $phpCode = '';
        $path = $this->getStubDir() . '/Validate/attribute.stub';
        foreach ($this->columns as $column) {
            $phpCode .= $this->getAttributeCode($column);
        }
        return str_replace('{LIST}', $phpCode, file_get_contents($path));
    }

    /**
     * @param array $column
     * @return string
     */
    protected function getAttributeCode(array &$column): string
    {
        $space = '            ';
        return sprintf(
            "%s'%s' => '字段 %s :%s',\n", $space, $column['column_name'], $column['column_comment'],$column['column_name']
        );
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