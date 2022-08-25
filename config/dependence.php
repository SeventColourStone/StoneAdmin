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

    \Webman\Push\Api::class =>  new \Webman\Push\Api(
        str_replace('0.0.0.0', '127.0.0.1', config('plugin.webman.push.app.api')),
        config('plugin.webman.push.app.app_key'),
        config('plugin.webman.push.app.app_secret'))
];