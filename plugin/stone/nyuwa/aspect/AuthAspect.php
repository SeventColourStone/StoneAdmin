<?php


namespace plugin\stone\nyuwa\aspect;


use plugin\stone\nyuwa\exception\TokenException;
use plugin\stone\nyuwa\helper\LoginUser;
use yzh52521\aop\AbstractAspect;
use yzh52521\aop\ProceedingJoinPoint;

class AuthAspect extends AbstractAspect
{
    public $annotations = [
        Auth::class
    ];

    /**
     * @var LoginUser
     */
    protected $loginUser;

    public function __construct(LoginUser $loginUser)
    {
        $this->loginUser = $loginUser;
    }

    /**
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     * @throws Exception
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        if ($this->loginUser->check()) {
            return $proceedingJoinPoint->process();
        }
        throw new TokenException(t('jwt.validate_fail'));
    }
}