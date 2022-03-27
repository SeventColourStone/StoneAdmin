<?php


namespace nyuwa\subscribes;


use nyuwa\event\UserLoginAfter;
use support\Log;

class UserLoginAfterSubscribe
{
    public function handleTest(UserLoginAfter $event)
    {
        var_dump('subscribe');
        var_dump($event);
    }

    public function subscribe($events)
    {
        $events->listen(
            UserLoginAfter::class,
            [TestSubscribe::class, 'handleTest']
        );
    }
}
