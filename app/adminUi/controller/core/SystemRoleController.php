<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 角色表
 * Class SystemRoleController
 * @package app\adminUi\controller\core
 */
class SystemRoleController extends NyuwaController
{

    public function index(){

        return view('core/systemRole/index');
    }

    public function add(){
        return view('core/systemRole/add');
    }

    public function edit(){
        return view('core/systemRole/edit');
    }

}
