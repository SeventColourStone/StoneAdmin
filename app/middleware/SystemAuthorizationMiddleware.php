<?php


namespace app\middleware;


use app\admin\service\system\SystemMenuService;
use app\admin\service\system\SystemPermissionService;
use app\admin\service\system\SystemUserService;
use DI\Annotation\Inject;
use nyuwa\exception\PermissionRefuseException;
use nyuwa\exception\TokenException;
use nyuwa\helper\LoginUser;
use nyuwa\helper\NyuwaCode;
use nyuwa\traits\ControllerTrait;
use support\Log;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;
use Webman\Route;

/**
 * 实现拦截器，做auth验证
 * Class ApiValidateMiddleware
 * @package nyuwa\middleware
 */
class SystemAuthorizationMiddleware implements MiddlewareInterface
{
    use ControllerTrait;

    /**
     * @var LoginUser
     */
    private $loginUser;


    /**
     * @var SystemUserService
     */
    protected $systemUserService;

    /**
     * @var SystemPermissionService
     */
    protected $systemPermissionService;


    public function process(Request $request, callable $handler): Response
    {

        $noLoginPath = [
            "system/login",
            "system/captcha",
        ];
        $routeName = $request->route->getName();
        var_dump("获取的路由别名:".$routeName);
        //最初的path
        $originPath = $request->path();
        $standardPath = trim($originPath,"/");
//        var_dump($standardPath);

        //去掉api，如果存在
        $arr = explode("/",$standardPath);
        $standardApiPath = $standardPath;
        if (count($arr) > 0 &&$arr[0] == "api"){
            array_shift($arr);
            $standardApiPath = implode("/",$arr);
        }
        Log::info("路由记录:".$standardApiPath);
        if (in_array($standardApiPath,$noLoginPath)){
            return $handler($request);
        }

        //ui入口
        $adminHome = "admin";
        if ($standardPath == $adminHome){
            //也需要登录验证。
//            try {
//                $this->loginUser = nyuwa_app(LoginUser::class);
//                $bool = $this->loginUser->check();
            return $handler($request);
//            }catch (\Exception $e){
            //重定向到登录页
//                return $this->error($e->getMessage());
//            }
        }

        //ui 渲染页面放行。
        $pos = strpos($standardPath, "ui");
        if ($pos !== false && $pos == 0){
            return $handler($request);
        }

        $this->loginUser = nyuwa_app(LoginUser::class);
        $bool = false;
        try {
            Log::info("当前未走验证时间:".microtime());
            $bool = $this->loginUser->check();
//            Log::info("当前验证时间:".microtime());
//            //验证过的才有资格验权限
//            $arr = explode("/",$originPath);
//            $permissionCode = implode(":",array_filter($arr));
//            $systemUserService = nyuwa_app(SystemUserService::class);
//            $codes = $systemUserService->getInfo()['codes'];
//            if (in_array("*",$codes)){
//                //放行
//                return $handler($request);
//            }
//            //可以访问的。
//            //权限配置 要么可配可以访问的，要么可配禁止访问的，二选一
//            if (in_array($permissionCode, $codes)) {
//                //放行
                return $handler($request);
//            }
//            return response($this->error(nyuwa_trans('system.no_permission') . ' -> [ ' . $request->path() . ' ]'));

            //permission 验证
        }catch (TokenException $e){
            return $this->error($e->getMessage(),NyuwaCode::TOKEN_EXPIRED);
        } catch (\Exception $e){
            return $this->error($e->getMessage());
        }
        return $handler($request);
    }

}
