<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 部门信息表
 * Class SystemDeptController
 * @package app\adminUi\controller\core
 */
class SystemDeptController extends NyuwaController
{

    public function index(){

        return view('core/systemDept/index');
    }

    public function add(){
        return view('core/systemDept/add');
    }

    public function edit(){
        return view('core/systemDept/edit');
    }

}
