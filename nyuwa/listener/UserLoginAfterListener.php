<?php


namespace nyuwa\listener;


use nyuwa\event\UserLoginAfter;
use support\Log;

class UserLoginAfterListener
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
     * @return void
     */
    public function handle(UserLoginAfter $event)
    {
        Log::info("TestListener事件触发了");
        var_dump("TestListener事件触发了");
        // 使用 $event->order 来访问订单 ...
    }
}
