<?php


namespace nyuwa\middleware;


use nyuwa\exception\TokenException;
use nyuwa\helper\LoginUser;
use nyuwa\helper\NyuwaCode;
use nyuwa\traits\ControllerTrait;
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