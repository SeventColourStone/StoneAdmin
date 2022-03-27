<?php

declare(strict_types=1);


return [
    /**
     * 不需要检查的路由，如果使用jwt提供的默认中间件，可以对某些不用做检验的路由进行配置，例如登录等
     * 具体的逻辑可以效仿JWT提供的默认中间件
     * [
     *      ["GET", "/index/test"],
     *      ["**", "/test"]
     * ]
     *
     * 第一个填写请求方法('**'代表支持所有的请求方法)，第二个填写路由路径('/**'代表支持所有的路径)
     * 如果数组中存在["**", "/**"]，则默认所有的请求路由都不做jwt token的校验，直接放行，如果no_check_route为一个空数组，则
     * 所有的请求路由都需要做jwt token校验
     * 路由路径支持正则的写法
     */
    'no_check_route' => [
        ["**", "/**"],
    ],

    /**
     * jwt使用到的缓存前缀
     * 使用独立的redis做缓存并存储所有的应用场景配置，这样比较好做分布式
     * key：cache_prefix:scene
     */
    'cache_prefix' => 'fastman:jwt',


    /**
     * 区分不同场景的token，比如你一个项目可能会有多种类型的应用接口鉴权,下面自行定义，我只是举例子
     * 下面的配置会自动覆盖根配置，比如application1会里面的数据会覆盖掉根数据
     * 下面的scene会和根数据合并
     * scene必须存在一个default
     * 什么叫根数据，这个配置的一维数组，除了scene都叫根配置
     */
    'scene' => [
        'default' => [],
        'application' => [
            'secret' => 'application', // 非对称加密使用字符串,请使用自己加密的字符串
            'login_type' => 'sso', //  登录方式，sso为单点登录，mpop为多点登录
            'sso_key' => 'uid',
            'ttl' => 7200, // token过期时间，单位为秒
        ],
        'application1' => [
            'secret' => 'application1', // 非对称加密使用字符串,请使用自己加密的字符串
            'login_type' => 'sso', //  登录方式，sso为单点登录，mpop为多点登录
            'sso_key' => 'uid',
            'ttl' => 7200, // token过期时间，单位为秒
        ],
        'application2' => [
            'secret' => 'application2', // 非对称加密使用字符串,请使用自己加密的字符串
            'login_type' => 'mppo', //  登录方式，sso为单点登录，mpop为多点登录
            'ttl' => 7200, // token过期时间，单位为秒
        ]
    ]

];
