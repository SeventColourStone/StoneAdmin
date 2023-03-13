<?php


namespace plugin\stone\nyuwa\auth\middleware;


use plugin\stone\nyuwa\auth\exception\JWTException;
use plugin\stone\nyuwa\auth\exception\TokenValidException;
use plugin\stone\nyuwa\auth\JWT;
use plugin\stone\nyuwa\auth\util\JWTUtil;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class JWTAuthDefaultSceneMiddleware implements MiddlewareInterface
{

    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWT();
    }

    public function process(Request $request, callable $handler): Response
    {
        /// 判断是否为noCheckRoute
        $path = $request->path();
        $method = $request->method();
//        if ($this->jwt->matchRoute('default', $method, $path)) {
//            return $handler->handle($request);
//        }

        $token = $request->header('Authorization') ?? '';
        if ($token == "") {
            throw new JWTException('Missing token', 400);
        }
        if (strlen($token) > 0) {
            $token = JWTUtil::handleToken($token);
            if ($token !== false && $this->jwt->verifyTokenByScene($token,'default')) {
                return $handler($request);
            }
        }
        throw new TokenValidException('Token authentication does not pass', 400);
    }
}