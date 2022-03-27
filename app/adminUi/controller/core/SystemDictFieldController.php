<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 字典表转换
 * Class SystemDictFieldController
 * @package app\adminUi\controller\core
 */
class SystemDictFieldController extends NyuwaController
{

    public function index(){

        return view('core/systemDictField/index');
    }

    public function add(){
        return view('core/systemDictField/add');
    }

    public function edit(){
        return view('core/systemDictField/edit');
    }

}
