<?php


namespace nyuwa\listener;


use nyuwa\event\UserLogout;
use support\Log;

class UserLogoutListener
{
    /**
     * 创建事件监听器。
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 处理事件。
     *
     * @param UserLogout $event
     * @return void
     */
    public function handle(UserLogout $event)
    {
        // 使用 $event->order 来访问订单 ...
    }
}
