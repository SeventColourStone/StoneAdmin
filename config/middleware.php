<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

return [

    // 全局中间件
    '' => [
        app\middleware\ActionHook::class,
//        app\middleware\AuthCheckTest::class,
//        app\middleware\AccessControlTest::class,
    ],
    // api应用中间件(应用中间件仅在多应用模式下有效)
    'api' => [
//        app\middleware\ApiOnly::class,
    ],
    "admin" => [
        \app\middleware\SystemAuthorizationMiddleware::class,
//        \app\middleware\SystemViewMiddleware::class
    ]
];
