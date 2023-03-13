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
    // 默认数据库
    'default' => 'mysql',

    // 各种数据库配置
    'connections' => [
        'mysql' => [
            'driver'      => env('DB_DRIVER','mysql'),
            'host'        => env('DB_HOST','127.0.0.1'),
            'port'        => env('DB_PORT',3306),
            'database'    => env('DB_DATABASE','development_stone_db'),
            'username'    => env('DB_USERNAME','root'),
            'password'    => env('DB_PASSWORD','root'),
            'unix_socket' => '',
            'charset'     => env('DB_CHARSET','utf8mb4'),
            'collation'   => env('DB_COLLATION','utf8mb4_general_ci'),
            'prefix'      => env('DB_PREFIX','stone_'),
            'strict'      => true,
            'engine'      => null,
            'options' => [
                \PDO::ATTR_TIMEOUT => 3
            ]
        ],
    ],
];