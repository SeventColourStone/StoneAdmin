<?php


namespace app\adminUi\controller\userCenter;


use nyuwa\NyuwaController;

class UserController extends NyuwaController
{

    public function index(){

        return view('system/userCenter/user/index');
    }

    public function add(){
        return view('system/userCenter/user/add');
    }

    public function edit(){
        return view('system/userCenter/user/edit');
    }


    public function person(){
        return view('system/userCenter/person');
    }


}
