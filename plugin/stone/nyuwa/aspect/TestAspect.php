<?php


namespace plugin\stone\nyuwa\aspect;


use Exception;
use plugin\stone\nyuwa\annotation\Test;
use plugin\stone\nyuwa\helper\LoginUser;
use yzh52521\aop\AbstractAspect;
use yzh52521\aop\interfaces\ProceedingJoinPointInterface;
use yzh52521\aop\ProceedingJoinPoint;

class TestAspect extends AbstractAspect
{
    public $annotations = [
        Test::class
    ];


    public function __construct()
    {
    }

    /**
     * @param ProceedingJoinPointInterface $joinPoint
     * @return mixed
     * @throws Exception
     */
    public function process(ProceedingJoinPointInterface $joinPoint)
    {

        var_dump('TestAspect an before');
        $res = $joinPoint->process();
        var_dump('TestAspect an after');
    }
}
