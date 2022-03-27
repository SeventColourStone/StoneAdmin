<?php


namespace app\adminUi\controller\userCenter;


use nyuwa\NyuwaController;

class DeptmentController extends NyuwaController
{

    public function index(){
        return view('system/userCenter/deptment/index');
    }

    public function save(){
        return view('system/userCenter/deptment/save');
    }
}
