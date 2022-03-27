<?php


namespace nyuwa\subscribes;


use nyuwa\event\UserLoginBefore;
use support\Log;

class UserLoginBeforeSubscribe
{
    public function handleTest(UserLoginBefore $event)
    {
        var_dump('subscribe');
        var_dump($event);
    }

    public function subscribe($events)
    {
        $events->listen(
            UserLoginBefore::class,
            [TestSubscribe::class, 'handleTest']
        );
    }
}
