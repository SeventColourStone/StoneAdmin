<?php

namespace app\controller;

use app\admin\service\system\SystemUserService;
use DI\Annotation\Inject;
use Linfo\Linfo;
use nyuwa\NyuwaController;
use nyuwa\NyuwaRequest;
use support\Db;
use support\Request;

class Index extends NyuwaController
{
    /**
     * @var SystemUserService
     * @Inject
     */
    private $systemUserService;

    /**
     * @throws \nyuwa\exception\TokenException
     */
    public function index(NyuwaRequest $request)
    {
        $ss = $this->systemUserService->firstByCache(['id'=>1]);
        $token = nyuwa_user("bbsweb")->createToken($ss);
        $Bool = nyuwa_user("bbsweb1")->check($token);
        var_dump($Bool);
        return $this->success($token);
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
