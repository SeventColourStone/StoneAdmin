<?php


namespace plugin\stone\nyuwa\middleware;


use plugin\stone\nyuwa\exception\TokenException;
use plugin\stone\nyuwa\helper\LoginUser;
use plugin\stone\nyuwa\helper\NyuwaCode;
use plugin\stone\nyuwa\traits\ControllerTrait;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class NyuwaAuthGuardMiddleware implements MiddlewareInterface
{
    use ControllerTrait;

    protected string $guardName;
    protected LoginUser $loginUser;

    public function __construct(string $guardName = null)
    {
        $this->guardName = $guardName;
    }

    /**
     * @inheritDoc
     */
    public function process(Request $request, callable $handler): Response
    {
        $this->loginUser = nyuwa_user($this->guardName);
        $bool = false;
        try {
            $bool = $this->loginUser->check();
            return $handler($request);

        }catch (TokenException $e){
            return $this->error($e->getMessage(),NyuwaCode::TOKEN_EXPIRED)->withStatus(401);
        } catch (\Exception $e){
            return $this->error($e->getMessage());
        }
        return $handler($request);
    }
}