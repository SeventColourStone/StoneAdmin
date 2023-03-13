<?php
namespace plugin\stone\app\middleware;

use plugin\admin\api\Auth;
use plugin\stone\app\service\system\SystemUserService;
use plugin\stone\nyuwa\exception\TokenException;
use plugin\stone\nyuwa\helper\LoginUser;
use plugin\stone\nyuwa\helper\NyuwaCode;
use plugin\stone\nyuwa\traits\ControllerTrait;
use ReflectionException;
use support\exception\BusinessException;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AccessControl implements MiddlewareInterface
{

    use ControllerTrait;

    private LoginUser $loginUser;

    protected SystemUserService $systemUserService;
    /**
     * @param Request $request
     * @param callable $handler
     * @return Response
     * @throws ReflectionException|BusinessException
     */
    public function process(Request $request, callable $handler): Response
    {
        $controller = $request->controller;
        $action = $request->action;
        var_dump("controller:".$controller);
        var_dump("action:".$action);

        $code = 0;
        $msg = '';
        try {
            $bool = $this->canAccess($controller, $action, $code, $msg);
            if (!$bool) {
                if ($request->expectsJson()) {
                    return $this->error($msg);
                } else {
                    return view('index/index', ['name' => $msg]);
                }
            }
        }catch (TokenException $e){
            $msg = $e->getMessage();
            $code = NyuwaCode::TOKEN_EXPIRED;
            return $this->error($e->getMessage(),NyuwaCode::TOKEN_EXPIRED)->withStatus(401);
        } catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        return $handler($request);

    }

    /**
     * @throws ReflectionException
     * @throws TokenException
     */
    public function canAccess(string $controller, string $action){
        // 无控制器信息说明是函数调用，函数不属于任何控制器，鉴权操作应该在函数内部完成。
        if (!$controller) {
            return true;
        }
        // 获取控制器鉴权信息
        $class = new \ReflectionClass($controller);
        $properties = $class->getDefaultProperties();
        $noNeedLogin = $properties['noNeedLogin'] ?? [];
        $noNeedAuth = $properties['noNeedAuth'] ?? [];

        // 不需要登录
        if (in_array($action, $noNeedLogin)) {
            return true;
        }

        // 获取登录信息
        $this->loginUser = nyuwa_app(LoginUser::class);

        $this->loginUser->check();
        //验证账号权限
        //不需要 permission 验证

        $permissionCode = $this->decodeLink($controller,$action);

        if (in_array($action, $noNeedAuth)) {
            return true;
        }else{
            //验证用户的系统内权限

        }
        return true;


    }

    /**
     * //从链接解析权限值
     * //默认system:user:index
     * //$controller = "plugin\stone\app\controller\setting\ConfigController"
     * //action = "getSysConfig"
     * @param string $controller
     * @param string $action
     * @return string
     */
    private function decodeLink(string $controller, string $action)
    {
        $controller = str_replace("plugin\stone\app\controller\\","",$controller);
        $controller = str_replace("Controller","",$controller);
        $permission = $controller."\\".$action;
        $permission = strtolower(str_replace("\\",":",$permission));
        var_dump($permission);
        return $permission;
    }

}