<?php


namespace app\adminUi\controller;


use nyuwa\NyuwaController;

class CommonController extends NyuwaController
{

    public function __404__(){
        return view('common/404');
    }

    public function __500__(){
        return view('common/500');
    }

    public function __403__(){
        return view('common/403');
    }

}