<?php


namespace app\admin\service\setting;

use nyuwa\generator\TableGenerator;

/**
 * 数据表设计服务
 * Class TableService
 * @package app\admin\service\core
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