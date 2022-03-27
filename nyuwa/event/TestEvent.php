<?php


namespace nyuwa\event;


class TestEvent
{
    public $order;

    /**
     * 创建一个新的事件实例。
     *
     * @return void
     */
    public function __construct(string $order)
    {
        $this->order = $order;
    }
}