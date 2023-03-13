<?php

declare(strict_types=1);

namespace plugin\stone\app\mapper\system;


use plugin\stone\app\model\system\SystemUploadfile;
use Illuminate\Database\Eloquent\Builder;
use plugin\stone\nyuwa\abstracts\AbstractMapper;

/**
 * 上传文件信息表
 * Class SystemUploadfileMapper
 * @package plugin\stone\app\mapper\core
 */
class SystemUploadfileMapper extends AbstractMapper
{
    /**
     * @var SystemUploadfile
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemUploadfile::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['storage_mode'])) {
            $query->where('storage_mode', $params['storage_mode']);
        }
        if (isset($params['origin_name'])) {
            $query->where('origin_name', 'like', '%'.$params['origin_name'].'%');
        }
        if (isset($params['storage_path'])) {
            $query->where('storage_path', 'like', $params['storage_path'].'%');
        }
        if (!empty($params['mime_type'])) {
            $query->where('mime_type', 'like', $params['mime_type'].'/%');
        }
        if (isset($params['minDate']) && isset($params['maxDate'])) {
            $query->whereBetween(
                'created_at',
                [$params['minDate'] . ' 00:00:00', $params['maxDate'] . ' 23:59:59']
            );
        }
        return $query;
    }

    public function realDelete(array $ids): bool
    {
        foreach ($ids as $id) {
            $model = $this->model::withTrashed()->find($id);
            if ($model) {
                $event = new \Mine\Event\RealDeleteUploadFile(
                    $model, $this->container->get(MineUpload::class)->getFileSystem()
                );
                $this->evDispatcher->dispatch($event);
                if ($event->getConfirm()) {
                    $model->forceDelete();
                }
            }
        }
        unset($event);
        return true;
    }

    /**
     * 检查数据库中是否存在该目录数据
     * @param string $path
     * @return bool
     */
    public function checkDirDbExists(string $path): bool
    {
        return $this->model::withTrashed()
            ->where('storage_path', $path)
            ->orWhere('storage_path', 'like', $path . '/%')
            ->exists();
    }
}
