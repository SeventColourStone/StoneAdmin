<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 岗位表
 * Class SystemPostController
 * @package app\adminUi\controller\core
 */
class SystemPostController extends NyuwaController
{

    public function index(){

        return view('core/systemPost/index');
    }

    public function add(){
        return view('core/systemPost/add');
    }

    public function edit(){
        return view('core/systemPost/edit');
    }

}
