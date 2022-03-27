<?php


namespace nyuwa\listener;


use nyuwa\event\ModelSavedEvent;
use support\Log;

class ModelSavedListener
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
    public function handle(ModelSavedEvent $event)
    {
        Log::info("ModelSavedListener事件触发了");
        var_dump("ModelSavedListener事件触发了");
        // 使用 $event->order 来访问订单 ...
    }
}
