<?php

declare(strict_types=1);

namespace app\adminUi\controller\generate;


use nyuwa\NyuwaController;

/**
 * 代码生成业务字段信息表
 * Class GenerateColumnsController
 * @package app\adminUi\controller\generate
 */
class GenerateColumnsController extends NyuwaController
{

    public function index(){

        return view('generate/generateColumns/index');
    }

    public function add(){
        return view('generate/generateColumns/add');
    }

    public function edit(){
        return view('generate/generateColumns/edit');
    }

}
