<?php


namespace app\adminUi\controller\userCenter;


use nyuwa\NyuwaController;

class RoleController extends NyuwaController
{

    public function index(){
        return view('system/userCenter/role/index');
    }

    public function save(){
        return view('system/userCenter/role/save');
    }
}
