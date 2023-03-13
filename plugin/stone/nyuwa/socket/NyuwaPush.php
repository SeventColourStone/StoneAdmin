<?php


namespace plugin\stone\nyuwa\socket;
use Webman\Push\Api;

class NyuwaPush
{

    public function send(){
        $api = new Api(
        // webman下可以直接使用config获取配置，非webman环境需要手动写入相应配置
            config('plugin.webman.push.app.api'),
            config('plugin.webman.push.app.app_key'),
            config('plugin.webman.push.app.app_secret')
        );
// 给订阅 user-1 的所有客户端推送 message 事件的消息
        $api->trigger('user-1', 'message', [
            'from_uid' => 2,
            'content'  => '你好，这个是消息内容'
        ]);
    }

}
