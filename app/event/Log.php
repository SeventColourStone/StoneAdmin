<?php


namespace App\event;


use app\admin\service\system\SystemOperLogService;
use DI\Annotation\Inject;
use nyuwa\event\Operation;

class Log
{
    protected $ignoreRouter = ['/login', '/getInfo', '/system/captcha'];
    /**
     * @var SystemOperLogService
     * @Inject
     */
    protected $systemOperLogService;

    public function operation(Operation $operation){

        var_dump("操作日志");
        $requestInfo = $operation->getRequestInfo();
        if (!in_array($requestInfo['router'], $this->ignoreRouter)) {
            $requestInfo['request_data'] = json_encode($requestInfo['request_data'], JSON_UNESCAPED_UNICODE);
//            $requestInfo['response_data'] = $requestInfo['response_data'];
            $this->systemOperLogService->save($requestInfo);
        }
    }

}