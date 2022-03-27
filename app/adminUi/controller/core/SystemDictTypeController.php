<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 字典类型表
 * Class SystemDictTypeController
 * @package app\adminUi\controller\core
 */
class SystemDictTypeController extends NyuwaController
{

    public function index(){

        return view('core/systemDictType/index');
    }

    public function add(){
        return view('core/systemDictType/add');
    }

    public function edit(){
        return view('core/systemDictType/edit');
    }

}
