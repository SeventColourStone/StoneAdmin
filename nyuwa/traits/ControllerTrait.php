<?php
namespace nyuwa\traits;

//use nyuwa\NyuwaRequest;
use app\admin\service\system\SystemUserService;
use nyuwa\helper\LoginUser;
use nyuwa\NyuwaResponse;
use Hyperf\Di\Annotation\Inject;
use Webman\Http\Request;

trait ControllerTrait
{
//    /**
//     * @Inject
//     * @var NyuwaRequest
//     */
//    protected $request;

    /**
     * 该方法会在请求前调用
     */
    public function beforeAction(Request $request)
    {

//        echo 'beforeAction';
//        $originPath = $request->path();
//        $arr = explode("/",$originPath);
//        $permissionCode = implode(":",array_filter($arr));
//        var_dump("权限code：".$permissionCode);
//        /**
//         * @var SystemUserService
//         */
//        $systemUserService = nyuwa_app(SystemUserService::class);
//        $codes = $systemUserService->getInfo()['codes'];
//        if (in_array("*",$codes)){
//            //放行
//            return true;
//        }
//        //可以访问的。
//        //权限配置 要么可配可以访问的，要么可配禁止访问的，二选一
//        if (in_array($permissionCode, $codes)) {
//            //放行
//            return true;
//        }
//        return response($this->error(nyuwa_trans('system.no_permission') . ' -> [ ' . $request->path() . ' ]'));
    }

    /**
     * @Inject
     * @var NyuwaResponse
     */
    protected $response;


    /**
     * @param string | array $msgOrData
     * @param array $data
     * @param int $code
     * @return NyuwaResponse
     */
    public function success($msgOrData = '', $data = [], $code = 200): NyuwaResponse
    {
        if (is_string($msgOrData) || is_null($msgOrData)) {
            return $this->response->success($msgOrData, $data, $code);
        } else if (is_array($msgOrData) || is_object($msgOrData)) {
            return $this->response->success(null, $msgOrData, $code);
        } else {
            return $this->response->success(null, $data, $code);
        }
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return NyuwaResponse
     */
    public function error(string $message = '', int $code = 500, array $data = []): NyuwaResponse
    {
        return $this->response->error($message, $code, $data);
    }

    /**
     * 跳转
     * @param string $toUrl
     * @param int $status
     * @param string $schema
     * @return NyuwaResponse
     */
    public function redirect(string $toUrl, int $status = 302, string $schema = 'http'): NyuwaResponse
    {
        //实现一个302
//        return $this->response->redirect($toUrl, $status, $schema);
    }

    /**
     * 下载文件
     * @param string $filePath
     * @param string $name
     * @return NyuwaResponse
     */
    public function _download(string $filePath, string $name = ''): NyuwaResponse
    {
        return $this->response->download($filePath, $name);
    }
}
