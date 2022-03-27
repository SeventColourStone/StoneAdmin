<?php



namespace app\adminUi\controller\dataCenter;


use nyuwa\NyuwaController;

/**
 * 字段典 页面
 * Class DictController
 * @package app\admin\controller\ui\system\dataCenter
 */
class FieldController extends NyuwaController
{
    //字典
    public function index(){
        return view("system/dataCenter/field/index");
    }


    public function add(){
        return view("system/dataCenter/field/add");
    }

    public function edit(){
        return view("system/dataCenter/field/edit");
    }
}
