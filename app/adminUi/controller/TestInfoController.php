<?php


namespace app\adminUi\controller;


use nyuwa\NyuwaController;

class TestInfoController extends NyuwaController
{

    public function index(){
        return view('test/hello', ['name' => 'webman']);
    }

}