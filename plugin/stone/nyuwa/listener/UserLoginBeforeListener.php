<?php


namespace plugin\stone\nyuwa\listener;


use plugin\stone\nyuwa\event\UserLoginBefore;
use support\Log;

class UserLoginBeforeListener
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
    public function handle(UserLoginBefore $event)
    {
        // 使用 $event->order 来访问订单 ...
    }
}
