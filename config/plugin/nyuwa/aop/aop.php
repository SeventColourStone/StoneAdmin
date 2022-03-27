<?php
//定义切入方法区分大小写
use app\admin\controller\api\TestController;
use nyuwa\aop\example\TestAspect;

return [
    TestAspect::class => [
        \app\admin\service\system\SystemUserService::class => [
            'test',
        ],
    ],
];
