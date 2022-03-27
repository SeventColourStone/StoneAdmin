<?php

return [
    'listener'  => [
//        \nyuwa\event\TestEvent::class => [
//            \nyuwa\listener\TestListener::class,
//        ],

//        \nyuwa\event\ApiAfter::class => [
//            \nyuwa\listener\ApiAfterListener::class,
//        ],
//
//        \nyuwa\event\ApiBefore::class => [
//            \nyuwa\listener\ApiBeforeListener::class,
//        ],

//        \nyuwa\event\Operation::class => [
//            \nyuwa\listener\OperationListener::class,
//        ],
        \nyuwa\event\UserLoginAfter::class => [
            \nyuwa\listener\UserLoginAfterListener::class,
        ],
        \nyuwa\event\UserLoginBefore::class => [
            \nyuwa\listener\UserLoginBeforeListener::class,
        ],
        \nyuwa\event\UserLogout::class => [
            \nyuwa\listener\UserLogoutListener::class,
        ],
        \nyuwa\event\ModelSavedEvent::class => [
            \nyuwa\listener\ModelSavedListener::class,
        ],
    ],
    'subscribe' => [
        \nyuwa\subscribes\TestSubscribe::class,
    ],
];
