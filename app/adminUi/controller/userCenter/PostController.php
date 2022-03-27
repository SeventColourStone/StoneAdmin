<?php


namespace app\adminUi\controller\userCenter;


use nyuwa\NyuwaController;

/**
 * 岗位ui
 * Class PostController
 * @package app\admin\controller\ui\system
 */
class PostController extends NyuwaController
{

    public function index(){
        return view('system/userCenter/post/index');
    }
    public function save(){
        return view('system/userCenter/post/save');
    }
}
