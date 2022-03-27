<?php

declare(strict_types=1);

namespace app\adminUi\controller\generate;


use nyuwa\NyuwaController;

/**
 * 业务表生成器表
 * Class GenerateTablesController
 * @package app\adminUi\controller\generate
 */
class GenerateTablesController extends NyuwaController
{

    public function index(){

        return view('generate/generateTables/index');
    }

    public function add(){
        return view('generate/generateTables/add');
    }

    public function edit(){
        return view('generate/generateTables/edit');
    }

}
