<?php


namespace nyuwa\generator;


use app\admin\model\setting\SettingGenerateColumns;
use app\admin\model\setting\SettingGenerateTables;
use nyuwa\exception\NormalStatusException;
use nyuwa\helper\Str;
use support\Db;
use Symfony\Component\Filesystem\Filesystem;

class PhinxSeederGenerator extends NyuwaGenerator implements CodeGenerator
{

    /**
     * @var string
     */
    protected string $codeContent;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var array
     */
    protected array $columns;

    protected array $keyColumns;

    /**
     * 设置生成信息
     * @param SettingGenerateTables $model
     * @return PhinxSeederGenerator
     */
    public function setGenInfo(string $tableName): PhinxSeederGenerator
    {
        $this->tableName = $tableName;
        $this->filesystem = nyuwa_app(Filesystem::class);
        $sql = <<<SQL
SELECT COLUMN_NAME,COLUMN_KEY,ORDINAL_POSITION
	, column_type, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH, data_type,COLUMN_COMMENT
FROM information_schema.COLUMNS
WHERE table_name = :table order by `ORDINAL_POSITION` ;
SQL;
        $columns = Db::select($sql,['table'=>$tableName]);
        $columns = json_decode(json_encode($columns),true);
        $this->keyColumns = [];
        foreach ($columns as $item){
            $this->keyColumns[$item['COLUMN_NAME']] = $item;
        }
//        var_dump(json_encode($this->keyColumns,JSON_UNESCAPED_UNICODE));
        return $this->placeholderReplace();

    }

    /**
     * 占位符替换
     */
    protected function placeholderReplace(): PhinxSeederGenerator
    {
        $this->setCodeContent(str_replace(
            $this->getPlaceHolderContent(),
            $this->getReplaceContent(),
            $this->readTemplate()
        ));

        return $this;
    }

    /**
     * 获取模板地址
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return $this->getStubDir() . '/phinxSeeder.stub';
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
     * 获取要替换的占位符
     */
    protected function getPlaceHolderContent(): array
    {
        return [
            '{CLASS_NAME}',
            '{DATA}',
            '{TABLE_NAME}',
        ];
    }

    /**
     * 获取要替换占位符的内容
     */
    protected function getReplaceContent(): array
    {
        return [
            Str::studly($this->tableName),
            $this->getData(),
            $this->tableName
        ];
    }

    //
    protected function getData(){
        $info = Db::table($this->tableName)->get()->toArray();

        $str = "";
        foreach ($info as $item){
            $str .= $this->getCodeTemplate($item);
        }
        return $str;
    }

    protected function getCodeTemplate($item): string
    {
        $str = "       [\n";
        $space = '      ';
        foreach ($item as $key => $val){
            $columnInfo = $this->keyColumns[$key];
            if (($columnInfo['CHARACTER_MAXIMUM_LENGTH']) > 0){ //字符串
                $str .= sprintf(
                    "%s'%s' => %s'%s',\n",
                    $space,  $key,
                    $space, $val
                );
            }else{
                if (empty($val) && trim(strlen($val)) == 0){
                    if ($columnInfo['IS_NULLABLE'] == "YES"){
                        $str .= sprintf(
                            "%s'%s' => %s null,\n",
                            $space,  $key,
                            $space
                        );
                    }else{
                        $str .= sprintf(
                            "%s'%s' => %s 0,\n",
                            $space,  $key,
                            $space
                        );
                    }
                }else{
                    $str .= sprintf(
                        "%s'%s' => %s '%s' ,\n",
                        $space,  $key,
                        $space, $val
                    );
                }
            }

        }
        $str .= "          ],\n";
        return $str;
    }


    public function generator()
    {
        $count = Db::table($this->tableName)->count();
        if ($count){
            $className = Str::studly($this->tableName);
            $path = BASE_PATH . "/database/seeds/";
            $this->filesystem->exists($path) || $this->filesystem->mkdir($path, 0755);
            $this->filesystem->dumpFile($path . "{$className}Init.php", $this->replace()->getCodeContent());
        }
    }

    public function preview()
    {
        return $this->replace()->getCodeContent();
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