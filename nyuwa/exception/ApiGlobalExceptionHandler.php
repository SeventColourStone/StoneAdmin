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

class ApiGlobalExceptionHandler extends Handler
{

    use ControllerTrait;
    public function report(Throwable $e)
    {
        parent::report($e);
        Log::error($e->getTraceAsString());
    }

    public function render(Request $request, Throwable $e): Response
    {
        if ($e->getPrevious() instanceof ValidationException){
            //属于 验证器异常，抛出json
             return $this->error($e->getMessage(),NyuwaCode::VALIDATE_FAILED);
        }

        if ($e->getPrevious() instanceof NormalStatusException || $e->getPrevious() instanceof TokenException){
            if ($e->getCode() != 200 && $e->getCode() != 0) {
                return $this->error($e->getMessage(),$e->getCode(),NyuwaCode::NORMAL_STATUS);
            }
            return $this->error($e->getMessage());
        }


        if ($e){
            //存在异常直接抛出
            //属于 验证器异常，抛出json
            var_dump("出现异常了:".$e->getMessage().$e->getCode().$e->getTraceAsString());
            return $this->error($e->getMessage(),$e->getCode());
        }
        return parent::render($request, $e);
    }
}
