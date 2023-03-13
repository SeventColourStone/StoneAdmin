<?php


namespace plugin\stone\app\controller;


use DI\Attribute\Inject;
use plugin\stone\app\service\system\SystemUserService;
use plugin\stone\app\validate\system\SystemUserValidate;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use plugin\stone\nyuwa\vo\UserServiceVo;
use support\Db;
use support\Request;

class LoginController extends NyuwaController
{

    /**
     * 不需要登录的方法
     * @var string[]
     */
    protected array $noNeedLogin = ['login', 'logout', 'captcha'];

    /**
     * 不需要鉴权的方法
     * @var string[]
     */
    protected array $noNeedAuth = ['info'];

    #[Inject]
    protected SystemUserService $systemUserService;


    #[Inject]
    protected SystemUserValidate $validate;

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
        var_dump($request->all());
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