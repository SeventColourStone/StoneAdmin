<?php


declare(strict_types = 1);
namespace nyuwa\event;

class UserLoginAfter
{
    public $userinfo;

    public $loginStatus = true;

    public $message;

    public $token;

    public function __construct(array $userinfo)
    {
        $this->userinfo = $userinfo;
    }
}