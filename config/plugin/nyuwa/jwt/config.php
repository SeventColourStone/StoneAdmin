<?php

return [
    'login_type' => env('JWT_LOGIN_TYPE', 'mpop'), //  登录方式，sso为单点登录，mpop为多点登录
    /**
     * 单点登录自定义数据中必须存在uid的键值，这个key你可以自行定义，只要自定义数据中存在该键即可
     */
    'sso_key' => 'uid',

    'secret' => env('JWT_SECRET', 'nyuwa'), // 非对称加密使用字符串,请使用自己加密的字符串
];