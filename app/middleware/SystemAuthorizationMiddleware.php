<?php


namespace App\middleware;


use app\admin\service\system\SystemUserService;
use nyuwa\exception\TokenException;
use nyuwa\helper\LoginUser;
use nyuwa\helper\NyuwaCode;
use nyuwa\traits\ControllerTrait;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

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

        /**
         * 不用授权的路由
         */
        $noLoginPath = [
            parse_url(config('plugin.webman.push.app.channel_hook'), PHP_URL_PATH),
            "plugin/webman/push/hook",
            "setting/config/getSysConfig",
            "setting/config/getConfigByKey",

            "system/login",
            "system/captcha",
            "core/systemLogin/login",
            "core/systemLogin/captcha",
        ];
        $routeName = $request->route->getName();
//        var_dump("获取的路由别名:".$routeName);
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
        if (in_array($standardApiPath,$noLoginPath)){
//            var_dump('不需要鉴权.'.$standardApiPath);
//            var_dump($handler($request));
            return $handler($request);
        }

        //测试环境不允许dml操作
//        $disableOp = ['update','delete','realDelete'];
//        $basename = pathinfo($originPath)['basename'];
//
//        if (in_array($basename,$disableOp)){
//            return $this->error("测试环境不允许该操作",NyuwaCode::NO_PERMISSION);
//        }


        $this->loginUser = nyuwa_app(LoginUser::class);
        $bool = false;
        try {
            $bool = $this->loginUser->check();
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
            return $this->error($e->getMessage(),NyuwaCode::TOKEN_EXPIRED)->withStatus(401);
        } catch (\Exception $e){
            return $this->error($e->getMessage());
        }
        return $handler($request);
    }

}