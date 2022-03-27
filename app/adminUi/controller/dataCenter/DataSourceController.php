<?php


namespace app\adminUi\controller\dataCenter;


use nyuwa\NyuwaController;

/**
 * 数据源管理
 * Class DataSourceController
 * @package app\admin\controller\ui\system\dataCenter
 */
class DataSourceController extends NyuwaController
{
    public function index(){
        return view("system/dataCenter/dataSource/index");
    }

    public function save(){
        return view("system/dataCenter/dataSource/save");
    }
}
