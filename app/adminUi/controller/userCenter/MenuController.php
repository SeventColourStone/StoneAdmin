<?php


namespace app\adminUi\controller\userCenter;


use nyuwa\NyuwaController;

/**
 * 菜单类
 * Class Menu
 * @package app\admin\controller\ui\system
 */
class MenuController extends NyuwaController
{
    public function index(){
        return view('system/userCenter/menu/index');
    }
    public function save(){
        return view('system/userCenter/menu/save');
    }
}
