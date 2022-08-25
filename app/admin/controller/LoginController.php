<?php


namespace app\admin\controller;


use app\admin\service\system\SystemUserService;
use app\admin\validate\system\SystemUserValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use nyuwa\vo\UserServiceVo;
use support\Request;

class LoginController extends NyuwaController
{
    /**
     * @Inject
     * @var SystemUserService
     */
    protected $systemUserService;


    /**
     * @Inject
     * @var SystemUserValidate
     */
    protected $validate;

    /**
     * 验证码
     */
    #[GetMapping("captcha")]
    public function captcha(): NyuwaResponse
    {
        return $this->response->responseImage($this->systemUserService->getCaptcha());
    }

    /**
     * 登录
     */
    #[PostMapping("login")]
    public function login(Request $request): NyuwaResponse
    {
        $requestData = $this->validate->scene("login")->check($request->all());
        $vo = new UserServiceVo();
        $vo->setUsername($requestData['username']);
        $vo->setPassword($requestData['password']);
        $vo->setVerifyCode($requestData['code']);
        return $this->success(['token' => $this->systemUserService->login($vo)]);
    }

    /**
     * 退出登录
     */
    public function logout(): NyuwaResponse
    {
        $this->systemUserService->logout();
        return $this->success();
    }

    /**
     * 用户信息
     */
    public function getInfo(): NyuwaResponse
    {
        return $this->success($this->systemUserService->getInfo());
    }

    /**
     * 刷新token
     */
    public function refresh(): NyuwaResponse
    {
        return $this->success(['token' => nyuwa_user()->refresh()]);
    }
}