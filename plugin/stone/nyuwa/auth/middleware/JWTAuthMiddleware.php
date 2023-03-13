<?php


namespace plugin\stone\nyuwa\auth\middleware;


use plugin\stone\nyuwa\auth\exception\TokenValidException;
use plugin\stone\nyuwa\auth\JWT;
use plugin\stone\nyuwa\auth\util\JWTUtil;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * jwt token 校验的中间件，只会jwt是否正常，不会区分场景校验
 * Class JWTAuthMiddleware
 * @package nyuwa\auth\middleware
 */
class JWTAuthMiddleware  implements MiddlewareInterface
{
    /*
    * @var JWT
     */
    protected $jwt;
    /**
     * JWTAuthMiddleware constructor.
     */
    public function __construct()
    {
        $this->jwt = new JWT();
    }
    public function process(Request $request, callable $handler): Response
    {
        $isValidToken = false;
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        $token = $request->header('Authorization') ?? '';
        if (strlen($token) > 0) {
            $token = JWTUtil::handleToken($token);
            if ($token !== false && $this->jwt->verifyToken($token)) {
                $isValidToken = true;
            }
        }
        if ($isValidToken) {
            return $handler($request);
        }

        throw new TokenValidException('Token authentication does not pass', 401);
    }
}
