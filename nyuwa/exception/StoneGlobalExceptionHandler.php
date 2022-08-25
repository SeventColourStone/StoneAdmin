<?php


namespace nyuwa\exception;


use Itwmw\Validation\Support\ValidationException;
use nyuwa\auth\exception\TokenValidException;
use nyuwa\helper\NyuwaCode;
use nyuwa\traits\ControllerTrait;
use support\exception\BusinessException;
use support\exception\Handler;
use support\Log;
use Throwable;
use Webman\Exception\ExceptionHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

class StoneGlobalExceptionHandler extends Handler
{

    use ControllerTrait;
    public function report(Throwable $e)
    {
        parent::report($e);
        Log::error($e->getTraceAsString());
    }

    public function render(Request $request, Throwable $exception): Response
    {
        if ($exception instanceof ValidationException){
            //属于 验证器异常，抛出json
             return $this->error($exception->getMessage(),NyuwaCode::VALIDATE_FAILED);
        }
        if ($exception instanceof NormalStatusException){
            if ($exception->getCode() != 200 && $exception->getCode() != 0) {
                return $this->error($exception->getMessage(),$exception->getCode());
            }
            return $this->error($exception->getMessage(),NyuwaCode::NORMAL_STATUS);
        }
        if ($exception instanceof TokenException){

            return $this->error($exception->getMessage(),NyuwaCode::TOKEN_EXPIRED)->withStatus(401);
        }

        if ($exception instanceof NoPermissionException){

            return $this->error($exception->getMessage(),NyuwaCode::NO_PERMISSION)->withStatus(403);
        }


        //上层调用链
        $lastEx = $exception->getPrevious();
        $lastMsg = "";
        if ($lastEx){
            $lastMsg = "上层调用链：".$lastEx->getFile().$lastEx->getLine().$lastEx->getMessage();
        }
        return $this->error($exception->getMessage());
//        return parent::render($request, $exception);
    }
}
