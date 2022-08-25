<?php

return [
    /**** 用户登录模块 ***/
    \nyuwa\NyuwaEvent::USER_LOGIN_BEFORE => [
        [app\event\User::class, 'loginBefore'],
        // ...其它事件处理函数...
    ],
    \nyuwa\NyuwaEvent::USER_LOGIN_AFTER => [
        [app\event\User::class, 'loginAfter'],
        // ...其它事件处理函数...
    ],
    \nyuwa\NyuwaEvent::USER_LOGOUT => [
        [app\event\User::class, 'logout'],
        // ...其它事件处理函数...
    ],

    /**** log ****/
    \nyuwa\NyuwaEvent::LOG_OPERATION => [
        [app\event\Log::class, 'operation'],
        // ...其它事件处理函数...
    ],

];
