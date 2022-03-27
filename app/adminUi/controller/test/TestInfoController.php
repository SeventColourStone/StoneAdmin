<?php

declare(strict_types=1);

namespace app\adminUi\controller\test;


use nyuwa\NyuwaController;

/**
 * 测试表
 * Class TestInfoController
 * @package app\adminUi\controller\test
 */
class TestInfoController extends NyuwaController
{

    public function index(){

        return view('test/testInfo/index');
    }

    public function add(){
        return view('test/testInfo/add');
    }

    public function edit(){
        return view('test/testInfo/edit');
    }

}
