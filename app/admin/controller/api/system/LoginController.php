<?php


namespace app\admin\controller\api\system;


use app\admin\service\system\SystemUserService;
use app\admin\validate\system\SystemUserValidate;
use DI\Annotation\Inject;
use Gregwar\Captcha\CaptchaBuilder;
use nyuwa\helper\LoginUser;
use nyuwa\NyuwaController;
use support\Request;
use W7\Validate\Exception\ValidateException;

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


    public function getCaptcha()
    {
        return $this->response->responseImage($this->systemUserService->getCaptcha());
    }

    /**
     * @param Request $request
     * @return \nyuwa\NyuwaResponse
     */
    public function login(Request $request): \nyuwa\NyuwaResponse
    {
        $data = $this->validate->scene("login")->check($request->all());
        $token = $this->systemUserService->login($data);
        return $this->success($token);
    }


    public function logout(): \nyuwa\NyuwaResponse
    {
        $this->systemUserService->logout();
        return $this->success();
    }

    /**
     */
    public function getInfo(): \nyuwa\NyuwaResponse
    {
        return $this->success($this->systemUserService->getInfo());
    }

    /**
     * 刷新token
     * @param LoginUser $user
     * @return \nyuwa\NyuwaResponse
     */
    public function refresh(LoginUser $user): \nyuwa\NyuwaResponse
    {
        return $this->success(['token' => $user->refresh()]);
    }


}
