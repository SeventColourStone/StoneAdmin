<?php

declare(strict_types=1);

namespace app\admin\service\setting;


use app\admin\mapper\setting\SettingGenerateTablesMapper;
use app\admin\model\setting\SettingGenerateTables;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;
use nyuwa\generator\ApiGenerator;
use nyuwa\generator\ControllerGenerator;
use nyuwa\generator\DtoGenerator;
use nyuwa\generator\MapperGenerator;
use nyuwa\generator\ModelGenerate;
use nyuwa\generator\ModelGenerator;
use nyuwa\generator\RequestGenerator;
use nyuwa\generator\ServiceGenerator;
use nyuwa\generator\SqlGenerator;
use nyuwa\generator\ValidateGenerator;
use nyuwa\generator\VueIndexGenerator;
use nyuwa\generator\VueSaveGenerator;
use support\Log;
use Symfony\Component\Filesystem\Filesystem;

class SettingGenerateTablesService extends AbstractService
{
    /**
     * @Inject
     * @var SettingGenerateTablesMapper
     */
    public $mapper;


    /**
     * @Inject
     * @var DataSourceService
     */
    protected $dataMaintainService;

    /**
     * @Inject
     * @var SettingGenerateColumnsService
     */
    protected $settingGenerateColumnsService;

    /**
     * @Inject
     * @var ModuleService
     */
    protected $moduleService;


    /**
     * 装载数据表
     * @param array $names
     * @return bool
     * @Transaction
     */
    public function loadTable(array $names): array
    {
        $tableIds = [];
        foreach ($names as $item) {
            $newItem = $item;
            unset($newItem['name']);
            unset($newItem['comment']);
            $tableInfo = [
                'table_name' => $item['name'],
                'table_comment' => $item['comment'],
                'type' => 'single',//tree
            ];
            if (!empty($newItem))
                $tableInfo = array_merge($tableInfo,$newItem);
            $id = $this->save($tableInfo);
            $tableIds []= $id;

            $columns = $this->dataMaintainService->getColumnList($item['name']);

            foreach ($columns as &$column) {
                $column['table_id'] = $id;
            }
            $this->settingGenerateColumnsService->save($columns);
        }

        return $tableIds;
    }

    /**
     * 同步数据表
     * @param int $id
     * @return bool
     * @Transaction
     */
    public function sync(string $id): bool
    {
        $table = $this->read($id);
        $columns = $this->dataMaintainService->getColumnList(
            str_replace(env('DB_PREFIX'), '', $table['table_name'])
        );
        $model = $this->settingGenerateColumnsService->mapper->getModel();
        $ids = $model->newQuery()->where('table_id', $table['id'])->pluck('id');

        $this->settingGenerateColumnsService->mapper->delete($ids->toArray());
        foreach ($columns as &$column) {
            $column['table_id'] = $id;
        }
        $this->settingGenerateColumnsService->save($columns);
        return true;
    }

    /**
     * 更新业务表
     * @param array $data
     * @return bool
     * @Transaction
     */
    public function updateTableAndColumns(array $data): bool
    {
        Log::info("获取的参数：");
        Log::info(json_encode($data,JSON_UNESCAPED_UNICODE));
        $id = $data['id'];
        $columns = $data['columns'];

        unset($data['columns']);

        if (!empty($data['belong_menu_id'])) {
            $data['belong_menu_id'] = is_array($data['belong_menu_id']) ? array_pop($data['belong_menu_id']) : $data['belong_menu_id'];
        } else {
            $data['belong_menu_id'] = 0;
        }

        $data['package_name'] = empty($data['package_name']) ? null : ucfirst($data['package_name']);
        $data['namespace'] = "app\\{$data['module_name']}";
        $data['generate_menus'] = implode(',', $data['generate_menus']);

        if (empty($data['options'])) {
            unset($data['options']);
        }

        // 更新业务表
        $this->update((string)$id, $data);
        Log::info("运行到这里更新业务表");

        // 更新业务字段表
        foreach ($columns as $column) {
            $bool = $this->settingGenerateColumnsService->update((string)$column['id'], $column);
            Log::info("运行到这里更新业务字段表:".$column['id']."  结果：".$bool);
        }

        return true;
    }

    /**
     * 生成代码
     * @param string $ids
     * @return string
     */
    public function generate(string $ids): string
    {
        try {
            $ids = explode(',', $ids);
            $this->initGenerateSetting();
            $adminId = nyuwa_user()->getId();
            foreach ($ids as $id) {
                $this->generateCodeFile( $id, $adminId);
            }

            return $this->packageCodeFile();
        }catch (\Exception $e){
            return "";
        }

    }

    /**
     * 生成步骤
     * @param int $id
     * @param string $adminId
     * @return SettingGenerateTables
     * @throws \Exception
     */
    protected function generateCodeFile(string $id, string $adminId): SettingGenerateTables
    {
//        try {


            /** @var SettingGenerateTables $model */
            $model = $this->read($id);

            $requestType = ['Create', 'Update'];

            $classList = [
                ControllerGenerator::class,
                ModelGenerate::class,//ModelGenerator::class,
                ServiceGenerator::class,
                MapperGenerator::class,
                ValidateGenerator::class, //RequestGenerator::class,
                ApiGenerator::class,
                VueIndexGenerator::class,
                VueSaveGenerator::class,
                SqlGenerator::class,
                DtoGenerator::class,
            ];

            foreach ($classList as $cls) {
                $class = nyuwa_app($cls);
                if (get_class($class) == 'nyuwa\generator\SqlGenerator'){
                    $class->setGenInfo($model, $adminId)->generator();
                } else {
                    $class->setGenInfo($model)->generator();
                }
            }
//        }catch (\Exception $e){
//            var_dump($e->getMessage());
//        }

        return $model;
    }

    /**
     * 打包代码文件
     * @return string
     */
    protected function packageCodeFile(): string
    {
        $zipFileName = BASE_PATH. '/runtime/stoneadmin.zip';
        $path = BASE_PATH . '/runtime/generate';
        // 删除老的压缩包
        @unlink($zipFileName);
        $archive = new \ZipArchive();
        $archive->open($zipFileName, \ZipArchive::CREATE);
        $files = nyuwa_files($path);
        foreach ($files as $file) {
            $archive->addFile(
                $path . '/' . $file->getFilename(),
                $file->getFilename()
            );
        }
        $this->addZipFile($archive, $path);
        $archive->close();
        return $zipFileName;
    }

    protected function addZipFile(\ZipArchive $archive, string $path): void
    {
        foreach (nyuwa_directories($path) as $directory) {
            if (is_dir($directory)) {
                $archive->addEmptyDir(str_replace(BASE_PATH. '/runtime/generate/', '', $directory));
                $files = nyuwa_files($directory);
                foreach ($files as $file) {
                    $archive->addFile(
                        $directory . '/' . $file->getFilename(),
                        str_replace(
                            BASE_PATH. '/runtime/generate/', '', $directory
                        ) . '/' . $file->getFilename()
                    );
                }
                $this->addZipFile($archive, $directory);
            }
        }
    }

    /**
     * 初始化生成设置
     */
    protected function initGenerateSetting(): void
    {
        // 设置生成目录
//        $genDirectory = BASE_PATH . '/runtime/generate';
//        /**
//         * @var Filesystem
//         */
//        $fs = nyuwa_app(Filesystem::class);
//        $fs = new Filesystem();
//
//        // 先删除再创建
//        $fs->remove($genDirectory);
//        $fs->mkdir($genDirectory);
    }

    /**
     * 获取所有模型
     * @return array
     */
    public function getModels(): array
    {
        $models = [];
        try {
            foreach ($this->moduleService->getModuleCache() as $item) if ($item['enabled']) {
                $path = sprintf("%s/app/%s/model/*", BASE_PATH, $item['name']);
                foreach (glob($path) as $file) {
                    if (is_dir($file)){
                        $nextPath = $file."/*";
                        foreach (glob($nextPath) as $nextFile) {
                            $models[] = sprintf(
                                '\app\%s\model\%s\%s',
                                $item['name'],
    //                            str_replace('.php', '', basename($nextFile)),
                                basename($file),
                                str_replace('.php', '', basename($nextFile))
                            );
                        }
                    }else{
                        $models[] = sprintf(
                            '\app\%s\model\%s',
                            $item['name'],
                            str_replace('.php', '', basename($file))
                        );
                    }
                }
            }
        }catch (\Exception $e){
            var_dump($e->getMessage());
        }

        return $models;
    }

    /**
     * 预览代码
     * @param string $id
     * @return array
     * @throws \Exception
     */
    public function preview(string $id): array
    {
        /** @var SettingGenerateTables $model */
        $model = $this->read($id);
//        try {
            return [
                [
                    'tab_name' => 'Controller.php',
                    'name' => 'controller',
                    'code' => nyuwa_app(ControllerGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'php'
                ],
                [
                    'tab_name' => 'Model.php',
                    'name' => 'model',
                    'code' => nyuwa_app(ModelGenerate::class)->setGenInfo($model)->preview(),
                    'lang' => 'php',
                ],
                [
                    'tab_name' => 'Service.php',
                    'name' => 'service',
                    'code' => nyuwa_app(ServiceGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'php',
                ],
                [
                    'tab_name' => 'Mapper.php',
                    'name' => 'mapper',
                    'code' => nyuwa_app(MapperGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'php',
                ],
                [
                    'tab_name' => 'Validate.php',
                    'name' => 'validate',
                    'code' => nyuwa_app(ValidateGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'php',
                ],
                [
                    'tab_name' => 'Dto.php',
                    'name' => 'dto',
                    'code' => nyuwa_app(DtoGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'php',
                ],
                [
                    'tab_name' => 'Api.js',
                    'name' => 'api',
                    'code' => nyuwa_app(ApiGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'javascript',
                ],
                [
                    'tab_name' => 'Index.vue',
                    'name' => 'index',
                    'code' => nyuwa_app(VueIndexGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'vue',
                ],
                [
                    'tab_name' => 'Save.vue',
                    'name' => 'save',
                    'code' => nyuwa_app(VueSaveGenerator::class)->setGenInfo($model)->preview(),
                    'lang' => 'vue',
                ],
                [
                    'tab_name' => 'Menu.sql',
                    'name' => 'sql',
                    'code' => nyuwa_app(SqlGenerator::class)->setGenInfo($model, nyuwa_user()->getId())->preview(),
                    'lang' => 'sql',
                ],
            ];

//        }catch (\Throwable $e){
//            var_dump($e->getMessage());
//            return [];
//        }

    }

}
