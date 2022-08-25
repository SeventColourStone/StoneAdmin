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

use App\middleware\SystemAuthorizationMiddleware;

return [
    // 全局中间件
    '' => [
        // ... 这里省略其它中间件
        //操作日记中间件
//        app\middleware\OperLogMiddleware::class,
    ],
    'admin' => [
        SystemAuthorizationMiddleware::class
    ]
];