<?php



namespace app\adminUi\controller\dataCenter;


use nyuwa\NyuwaController;

/**
 * 字典ui,字典的curd 页面都写在这里
 * Class DictController
 * @package app\admin\controller\ui\system\dataCenter
 */
class DictController extends NyuwaController
{
    //字典
    public function index(){
        return view("system/dataCenter/dict/index");
    }


    public function dictTypeAdd(){
        return view("system/dataCenter/dict/dictType/add");
    }
    public function dictTypeEdit(){
        return view("system/dataCenter/dict/dictType/edit");
    }
    public function dictDataAdd(){
        return view("system/dataCenter/dict/dictData/add");
    }
    public function dictDataEdit(){
        return view("system/dataCenter/dict/dictData/edit");
    }

    public function testChild(){
        return view("system/dataCenter/dict/testChild");
    }
}
