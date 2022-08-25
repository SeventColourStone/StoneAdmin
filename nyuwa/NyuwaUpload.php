<?php


namespace nyuwa;


use app\admin\service\setting\SettingConfigService;
use DI\Annotation\Inject;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use nyuwa\event\UploadAfter;
use nyuwa\exception\NormalStatusException;
use nyuwa\helper\Str;
use Shopwwi\WebmanFilesystem\FilesystemFactory;
use support\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tinywan\Storage\Storage;
use Webman\Http\UploadFile;

class NyuwaUpload
{

    /**
     * @var SettingConfigService
     */
    private $settingConfigService;

    /**
     * @var FilesystemFactory
     */
    protected  $filesystem;

    /**
     * 存储配置信息
     * @var array
     */
    protected $config;

    /**
     * MineUpload constructor.
     */
    public function __construct()
    {
        $this->config = config('file.storage');
        $this->settingConfigService = Container::get(SettingConfigService::class);
        $this->filesystem = FilesystemFactory::get($this->getStorageMode());
    }

    /**
     * 获取文件操作处理系统
     * @return Filesystem
     */
    public function getFileSystem(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * 上传文件
     * @param array $config
     * @return array
     */
    public function upload(UploadFile $uploadedFile, array $config = []): array
    {
        return $this->handleUpload($uploadedFile, $config);
    }

    /**
     * 处理上传
     * @param UploadFile $uploadedFile
     * @param array $config
     * @return array
     * @throws FilesystemException
     */
    protected function handleUpload(UploadFile $uploadedFile, array $config): array
    {
        $path = $this->getPath($config['path'] ?? null, $this->getMappingMode() !== 1);
        $pathInfo = pathinfo($uploadedFile->getUploadName());
        $filename = $this->getNewName() . '.' . Str::lower($pathInfo['extension']);
        $stream = fopen($uploadedFile->getRealPath(), 'r+');
        $this->filesystem->writeStream(
            $path . '/' .$filename,
            $stream
        );
        fclose($stream);

        $fileInfo = [
            'storage_mode' => $this->getMappingMode(),
            'origin_name' => $uploadedFile->getUploadName(),
            'object_name' => $filename,
            'mime_type' => $uploadedFile->getUploadMineType(),
            'storage_path' => $path,
            'suffix' => Str::lower($uploadedFile->getExtension()),
            'size_byte' => $uploadedFile->getSize(),
            'size_info' => format_size($uploadedFile->getSize() * 1024),
            'url' => $this->assembleUrl($config['path'] ?? null, $filename),
        ];

        //发送上传文件成功事件
        nyuwa_event(new UploadAfter($fileInfo));

        return $fileInfo;
    }

    /**
     * 保存网络图片
     * @param array $data
     * @return array
     */
    public function handleSaveNetworkImage(array $data): array
    {
        $path = $this->getPath($data['path'] ?? null, $this->getMappingMode() !== 1);
        $filename = $this->getNewName() . '.jpg';

        try {
            $content = file_get_contents($data['url']);

            $handle = fopen($data['url'], 'rb');
            $meta = stream_get_meta_data($handle);
            fclose($handle);

            $dataInfo = $meta['wrapper_data']['headers'] ?? $meta['wrapper_data'];
            $size = 0;

            foreach ($dataInfo as $va) {
                if ( preg_match('/length/iU', $va) ) {
                    $ts = explode(':', $va);
                    $size = intval(trim(array_pop($ts)));
                    break;
                }
            }

            if (!$this->filesystem->write($path . '/' . $filename, $content)) {
                throw new \Exception(nyuwa_trans('network_image_save_fail'));
            }

        } catch (\Throwable $e) {
            throw new NormalStatusException($e->getMessage(), 500);
        }

        $fileInfo = [
            'storage_mode' => $this->getMappingMode(),
            'origin_name' => md5((string) time()).'.jpg',
            'object_name' => $filename,
            'mime_type' => 'image/jpg',
            'storage_path' => $path,
            'suffix' => 'jpg',
            'size_byte' => $size,
            'size_info' => format_size($size * 1024),
            'url' => $this->assembleUrl($data['path'] ?? null, $filename),
        ];

        nyuwa_event(new UploadAfter($fileInfo));

        return $fileInfo;
    }

    /**
     * @param string $config
     * @param false $isContainRoot
     * @return string
     */
    protected function getPath(?string $path = null, bool $isContainRoot = false): string
    {
        $uploadfile = $isContainRoot ? '/'.env('UPLOAD_PATH', 'uploadfile').'/' : '';
        return empty($path) ? $uploadfile . date('Ymd') : $uploadfile . $path;
    }

    /**
     * 创建目录
     * @param string $name
     * @return bool
     * @throws FilesystemException
     */
    public function createUploadDir(string $name): bool
    {
        return $this->filesystem->createDirectory($name);
    }

    /**
     * 获取目录内容
     * @param string $path
     * @return array
     * @throws FilesystemException
     */
    public function listContents(string $path = ''): array
    {
        return $this->filesystem->listContents($path);
    }

    /**
     * 获取目录
     * @param string $path
     * @param bool $isChildren
     * @return array
     * @throws FilesystemException
     */
    public function getDirectory(string $path, bool $isChildren): array
    {
        $contents = $this->filesystem->listContents($path, $isChildren);
        $dirs = [];
        foreach ($contents as $content) {
            if ($content['type'] == 'dir') {
                $dirs[] = $content;
            }
        }
        return $dirs;
    }

    /**
     * 组装url
     * @param string $path
     * @param string $filename
     * @return string
     */
    public function assembleUrl(?string $path, string $filename): string
    {
        return $this->getPath($path, true) . '/' . $filename;
    }

    /**
     * 获取存储方式
     * @return string
     */
    public function getStorageMode(): string
    {
        return $this->settingConfigService->getConfigByKey('site_storage_mode')['value'] ?? 'local';
    }

    /**
     * 获取编码后的文件名
     * @return string
     */
    public function getNewName(): string
    {
        return snowflake_id();
    }

    /**
     * @return int
     */
    protected function getMappingMode(): int
    {
        $mode = $this->getStorageMode();
        if ($mode == "local"){
            return 1;
        }elseif ($mode == "oss"){
            return 2;
        }elseif ($mode == "qiniu"){
            return 3;
        }elseif ($mode == "cos"){
            return 4;
        }else{
            return 1;
        }
    }

    /**
     */
    protected function getProtocol(): string
    {
        return request()->protocolVersion();
    }
}