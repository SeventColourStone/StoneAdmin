<?php


namespace app\adminUi\controller\devCenter;


use nyuwa\NyuwaController;

class FormController extends NyuwaController
{

    public function index(){
        return view("system/devCenter/index");
    }

}