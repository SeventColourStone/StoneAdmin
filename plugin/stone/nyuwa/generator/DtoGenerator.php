<?php
/**

 */

declare(strict_types=1);
namespace plugin\stone\nyuwa\generator;


use Illuminate\Support\Collection;
use plugin\stone\app\model\setting\SettingGenerateColumns;
use plugin\stone\app\model\setting\SettingGenerateTables;
use plugin\stone\nyuwa\exception\NormalStatusException;
use plugin\stone\nyuwa\helper\Str;
use Symfony\Component\Filesystem\Filesystem;

class DtoGenerator extends NyuwaGenerator implements CodeGenerator
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
     * @var Collection
     */
    protected Collection $columns;

    /**
     * 设置生成信息
     * @param SettingGenerateTables $model
     * @return DtoGenerator
     */
    public function setGenInfo(SettingGenerateTables $model): DtoGenerator
    {
        $this->model = $model;
        $this->filesystem = nyuwa_app(Filesystem::class);
        if (empty($model->module_name) || empty($model->menu_name)) {
            throw new NormalStatusException(nyuwa_trans('setting.gen_code_edit'));
        }
        $this->setNamespace($this->model->namespace);
        $this->setPackage(env("GENCODE_PACKAGE","business"));
        $this->columns = SettingGenerateColumns::query()
            ->where('table_id', $model->id)->orderByDesc('sort')
            ->get([ 'column_name', 'column_comment' ]);

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
            $path = BASE_PATH . "/runtime/generate/php/app/{$package}/dto/";
        } else {
            $path = BASE_PATH . "/app/{$package}/dto/";
        }
        if (!empty($genFilePath)){
            $path = $genFilePath."/php/plugin/stone/app/dto/{$package}/";
        }
        $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);
        $this->filesystem->dumpFile($path . "{$this->getClassName()}.php", $this->replace()->getCodeContent());

//        var_dump("文件生成DtoGenerator");
    }

    /**
     * @return string
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
        return $this->getStubDir().'/dto.stub';
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
    protected function placeholderReplace(): DtoGenerator
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
            '{LIST}',
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
            $this->getList(),
        ];
    }

    /**
     * 初始化命名空间
     * @return string
     */
    protected function initNamespace(): string
    {
        return $this->getNamespace() . "\\dto\\".$this->getPackage();;
    }

    /**
     * 获取控制器注释
     * @return string
     */
    protected function getComment(): string
    {
        return $this->model->menu_name. 'Dto （导入导出）';
    }

    /**
     * 获取类名称
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->getBusinessName().'Dto';
    }

    /**
     * @return string
     */
    protected function getList(): string
    {
        $phpCode = '';
        foreach ($this->columns as $index => $column) {
            $phpCode .= str_replace(
                ['NAME', 'INDEX', 'FIELD'],
                [$column['column_comment'] ?: $column['column_name'], $index, $column['column_name']],
                $this->getCodeTemplate()
            );
        }
        return $phpCode;
    }

    protected function getCodeTemplate(): string
    {
        return sprintf(
            "    /**\n    * NAME\n    %s\n    */\n    %s\n    \n",
            '* @ExcelProperty(value= "NAME", index= INDEX)',
            'public string $FIELD;'
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