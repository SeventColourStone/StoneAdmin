<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 字典数据表
 * Class SystemDictDataController
 * @package app\adminUi\controller\core
 */
class SystemDictDataController extends NyuwaController
{

    public function index(){

        return view('core/systemDictData/index');
    }

    public function add(){
        return view('core/systemDictData/add');
    }

    public function edit(){
        return view('core/systemDictData/edit');
    }

}
