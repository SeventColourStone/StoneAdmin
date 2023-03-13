<?php

namespace plugin\stone\app\controller;

use plugin\stone\nyuwa\NyuwaController;
use support\Redis;
use support\Request;

class IndexController extends NyuwaController
{

    public function index()
    {
        return view('index/index', ['name' => 'stone']);
    }

}
