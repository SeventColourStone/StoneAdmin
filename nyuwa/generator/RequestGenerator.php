<?php
/** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
/**

 */

declare(strict_types=1);
namespace nyuwa\generator;


use app\admin\model\setting\SettingGenerateColumns;
use app\admin\model\setting\SettingGenerateTables;
use nyuwa\exception\NormalStatusException;
use nyuwa\helper\Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * 验证器生成
 * Class RequestGenerator
 * @package Mine\Generator
 */
class RequestGenerator extends NyuwaGenerator implements CodeGenerator
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
     * @return RequestGenerator
     */
    public function setGenInfo(SettingGenerateTables $model): RequestGenerator
    {
        $this->model = $model;
        $this->filesystem = nyuwa_app(Filesystem::class);
        if (empty($model->module_name) || empty($model->menu_name)) {
            throw new NormalStatusException(nyuwa_trans('setting.gen_code_edit'));
        }
        $this->setNamespace($this->model->namespace);

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
    public function generator(): void
    {
        $module = Str::camel($this->model->module_name);
        if ($this->model->generate_type == '0') {
            $path = BASE_PATH . "/runtime/generate/php/app/{$module}/request/";
        } else {
            $path = BASE_PATH . "/app/{$module}/request/";
        }
        $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);
        $this->filesystem->dumpFile($path . "{$this->getClassName()}.php", $this->replace()->getCodeContent());
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
        return $this->getStubDir() . '/Request/main.stub';
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
    protected function placeholderReplace(): RequestGenerator
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
        ];
    }

    /**
     * 初始化命名空间
     * @return string
     */
    protected function initNamespace(): string
    {
        return $this->getNamespace() . "\\Request";
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
        return $this->getBusinessName() . 'Request';
    }

    /**
     * 获取验证数据规则
     * @return string
     */
    protected function getRules(): string
    {
        $phpContent = '';
        $createCode = '';
        $updateCode = '';
        $path = $this->getStubDir() . '/Request/rule.stub';

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
            ['新增数据验证规则', 'saveRules', $createCode],
            file_get_contents($path)
        );
        $phpContent .= str_replace(
            ['{METHOD_COMMENT}', '{METHOD_NAME}', '{LIST}'],
            ['更新数据验证规则', 'updateRules', $updateCode],
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
        $space = '            ';
        return sprintf(
            "%s//%s 验证\n%s'%s' => 'required',\n",
            $space,  $column['column_comment'],
            $space, $column['column_name']
        );
    }

    /**
     * @return string
     */
    protected function getAttributes(): string
    {
        $phpCode = '';
        $path = $this->getStubDir() . '/Request/attribute.stub';
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
            "%s'%s' => '%s',\n", $space, $column['column_name'], $column['column_comment']
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