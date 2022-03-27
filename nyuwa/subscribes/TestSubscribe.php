<?php


namespace nyuwa\subscribes;


use nyuwa\event\TestEvent;

class TestSubscribe
{
    public function handleTest(TestEvent $event)
    {
        var_dump('subscribe');
        var_dump($event);
    }

    public function subscribe($events)
    {
        $events->listen(
            TestEvent::class,
            [TestSubscribe::class, 'handleTest']
        );
    }
}