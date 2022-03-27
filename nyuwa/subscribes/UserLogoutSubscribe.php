<?php


namespace nyuwa\subscribes;


use nyuwa\event\UserLogout;
use support\Log;

class UserLogoutSubscribe
{
    public function handleTest(UserLogout $event)
    {
        var_dump('subscribe');
        var_dump($event);
    }

    public function subscribe($events)
    {
        $events->listen(
            UserLogout::class,
            [TestSubscribe::class, 'handleTest']
        );
    }
}
