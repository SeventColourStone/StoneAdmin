<?php


namespace app\adminUi\controller;


use nyuwa\NyuwaController;

/**
 * admin ui路由规则
 * AdminController 默认直接 "ui/" + action 访问
 * 其他按 "ui/" + 控制器下包名 + "/" + 控制器名 + "/" + action 访问
 *
 * Class AdminController
 * @package app\adminUi\controller
 */
class AdminController extends NyuwaController
{

    public function admin(){
        return view('index');
    }

    public function login(){
        return view('login');
    }

    public function dashboard(){
        return view('system/home/dashboard');
    }

    public function dataAnalysis(){
        return view('system/home/dataAnalysis');
    }



}