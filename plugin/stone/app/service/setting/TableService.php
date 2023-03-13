<?php


namespace plugin\stone\app\service\setting;

use plugin\stone\nyuwa\generator\TableGenerator;

/**
 * 数据表设计服务
 * Class TableService
 * @package plugin\stone\app\service\core
 */
class TableService
{
    /**
     * 获取表前缀信息
     * @return string
     */
    public function getTablePrefix(): string
    {
        return env('DB_PREFIX', '');
    }

    /**
     * 创建数据表
     * @param array $data
     * @return bool
     */
    public function createTable(array $data): bool
    {
        return nyuwa_app(TableGenerator::class)->setTableInfo($data)->createTable();
    }
}