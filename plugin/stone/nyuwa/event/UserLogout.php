<?php

declare(strict_types = 1);
namespace plugin\stone\nyuwa\event;

class UserLogout
{
    public $userinfo;

    public function __construct(array $userinfo)
    {
        $this->userinfo = $userinfo;
    }
}