<?php

declare(strict_types=1);

namespace plugin\stone\app\service\system;


use plugin\stone\app\mapper\system\SystemUploadfileMapper;
use DI\Attribute\Inject;
use Illuminate\Database\Eloquent\Collection;
use plugin\stone\nyuwa\abstracts\AbstractService;
use plugin\stone\nyuwa\helper\Str;
use plugin\stone\nyuwa\NyuwaUpload;
use Webman\Http\UploadFile;

class SystemUploadfileService extends AbstractService
{
    /**
     * @var SystemUploadfileMapper
     */
    public $mapper;


    /**
     * @var NyuwaUpload
     */
    protected $nyuwaUpload;


    public function __construct(SystemUploadfileMapper $mapper,NyuwaUpload $nyuwaUpload)
    {
        $this->mapper = $mapper;
        $this->nyuwaUpload = $nyuwaUpload;
    }

    /**
     * 上传文件
     * @param UploadFile $uploadedFile
     * @param array $config
     * @return array
     */
    public function upload(UploadFile $uploadedFile, array $config = []): array
    {
        $data = $this->nyuwaUpload->upload($uploadedFile, $config);
        if ($this->save($data)) {
            return $data;
        } else {
            return [];
        }
    }

    /**
     * 获取当前目录下所有文件（包含目录）
     * @param array $params
     * @return array
     */
    public function getAllFile(array $params = []): array
    {
        return $this->getArrayToPageList($params);
    }

    /**
     * 数组数据搜索器
     * @param Collection $collect
     * @param array $params
     * @return Collection
     */
    protected function handleArraySearch(Collection $collect, array $params): Collection
    {
        if ($params['name'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row['name'], $params['name']);
            });
        }

        if ($params['label'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row['label'], $params['label']);
            });
        }
        return $collect;
    }

    /**
     * 设置需要分页的数组数据
     * @param array $params
     * @return array
     */
    protected function getArrayData(array $params = []): array
    {
        $directory = $this->nyuwaUpload->getDirectory($params['storage_path'] ?? '');

        $params['select'] = [
            'id',
            'origin_name',
            'object_name',
            'mime_type',
            'url',
            'size_info',
            'storage_path',
            'created_at'
        ];

        $params['select'] = implode(',', $params['select']);

        return array_merge($directory, $this->getList($params));
    }

    /**
     * 保存网络图片
     * @param array $data ['url', 'path']
     * @return array
     */
    public function saveNetworkImage(array $data): array
    {
        $data = $this->nyuwaUpload->handleSaveNetworkImage($data);
        if ($this->save($data)) {
            return $data;
        } else {
            return [];
        }
    }

}
