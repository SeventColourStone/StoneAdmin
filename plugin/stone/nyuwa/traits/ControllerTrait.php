<?php
namespace plugin\stone\nyuwa\traits;

use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaResponse;
use Webman\Http\Request;

trait ControllerTrait
{

    /**
     * 该方法会在请求前调用
     */
    public function beforeAction(Request $request)
    {

    }

    #[Inject]
    protected NyuwaResponse $response;


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
