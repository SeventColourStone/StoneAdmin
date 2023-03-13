<?php

namespace app\controller;

use app\model\Test;
use DI\Attribute\Inject;
use support\Request;

class IndexController
{
    public function index(Request $request)
    {


        return response('hello stone :)');
    }

    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
