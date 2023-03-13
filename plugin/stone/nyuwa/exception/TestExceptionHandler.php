<?php


namespace plugin\stone\nyuwa\exception;


use support\exception\BusinessException;
use support\exception\Handler;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;

class TestExceptionHandler extends Handler
{

    public $dontReport = [
        BusinessException::class,
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render(Request $request, Throwable $exception): Response
    {
        $path = $request->path();
        var_dump("接口：$path 报错异常:");
        var_dump("异常信息：".$exception->getMessage());
        return parent::render($request, $exception);
    }

}