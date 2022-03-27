<?php

declare(strict_types=1);

namespace app\adminUi\controller\core;


use nyuwa\NyuwaController;

/**
 * 系统公告表
 * Class SystemNoticeController
 * @package app\adminUi\controller\core
 */
class SystemNoticeController extends NyuwaController
{

    public function index(){

        return view('core/systemNotice/index');
    }

    public function add(){
        return view('core/systemNotice/add');
    }

    public function edit(){
        return view('core/systemNotice/edit');
    }

}
