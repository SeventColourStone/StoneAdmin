<?php

declare(strict_types=1);

namespace app\adminUi\controller\test;


use nyuwa\NyuwaController;

/**
 * 测试表
 * Class TestInfo1Controller
 * @package app\adminUi\controller\test
 */
class TestInfo1Controller extends NyuwaController
{

    public function index(){

        return view('test/testInfo1/index');
    }

    public function add(){
        return view('test/testInfo1/add');
    }

    public function edit(){
        return view('test/testInfo1/edit');
    }

}
