<?php


namespace App\middleware;


use app\admin\service\system\SystemMenuService;
use nyuwa\event\Operation;
use nyuwa\helper\LoginUser;
use nyuwa\helper\Str;
use nyuwa\NyuwaEvent;
use Webman\Event\Event;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class OperLogMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $path = $request->path();
        var_dump("当前的路径:" . $path);

        $isDownload = false;
        if (!empty($request->header('content-description')) && !empty($request->header('content-transfer-encoding'))) {
            $isDownload = true;
        }
        /**
         * @var Response
         */
        $response = $handler($request);
        $code = trim($path,"/");
        $code = str_replace("/",":",$code);
        Event::emit(NyuwaEvent::LOG_OPERATION,new Operation($this->getRequestInfo([
            'code' => !empty($code) ? $code : '',
            'name' => "",
            'response_code' => $response->getStatusCode(),
            'response_data' => $isDownload ? '文件下载' : $response->rawBody()
        ])));

        // 请求继续穿越
        return $response;
    }

    /**
     * @param array $data
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getRequestInfo(array $data): array
    {
        $request = request();
        $loginUser = nyuwa_app(LoginUser::class);

        $operationLog = [
            'time' => date('Y-m-d H:i:s'),
            'method' => $request->method(),
            'router' => $request->path(),
            'protocol' => $request->protocolVersion(),
            'ip' => $request->getRemoteIp(),
            'ip_location' => Str::ipToRegion($request->getRemoteIp()),
            'service_name' => $data['name'] ?: $this->getOperationMenuName($data['code']),
            'request_data' => $request->all(),
            'response_code' => $data['response_code'],
            'response_data' => $data['response_data'],
        ];
        try {
            $operationLog['username'] = $loginUser->getUsername();
        } catch (\Exception $e) {
            $operationLog['username'] = nyuwa_trans('system.no_login_user');
        }

        return $operationLog;
    }

    /**
     * 获取菜单名称
     * @param string $code
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getOperationMenuName(string $code): string
    {
        return nyuwa_app(SystemMenuService::class)->findNameByCode($code);
    }
}