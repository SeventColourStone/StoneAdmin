<?php
return [
    'enable'       => true,
    'websocket'    => 'websocket://0.0.0.0:'.env('WEBSOCKET_LISTEN',3131),
    'api'          => 'http://0.0.0.0:'.env('WEBSOCKET_API_LISTEN',3232),
    'app_key'      => 'fef22e42b91aefd287acc2aded5234d0',
    'app_secret'   => '24ae0a9de5c4861355d6c7a82d05f74f',
    'channel_hook' => 'http://127.0.0.1:8787/plugin/webman/push/hook',
    'auth'         => '/plugin/webman/push/auth'
];