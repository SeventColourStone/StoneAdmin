<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 菜单表
 * Class SystemMenuController
 * @package app\adminUi\controller\core
 */
class SystemMenuController extends NyuwaController
{

    public function index(){

        return view('core/systemMenu/index');
    }

    public function treeIndex(){

        return view('core/systemMenu/treeIndex');
    }

    public function treeIdx(){

        return view('core/systemMenu/treeIdx');
    }

    public function add(){
        return view('core/systemMenu/add');
    }

    public function edit(){
        return view('core/systemMenu/edit');
    }

}
