<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 用户表
 * Class SystemUserController
 * @package app\adminUi\controller\core
 */
class SystemUserController extends NyuwaController
{

    public function index(){

        return view('core/systemUser/index');
    }

    public function add(){
        return view('core/systemUser/add');
    }

    public function edit(){
        return view('core/systemUser/edit');
    }

}
