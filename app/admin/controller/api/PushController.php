<?php


namespace app\admin\controller\api;


use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use Webman\Push\Api;
use support\Request;


class PushController extends NyuwaController
{
    /**
     * @Inject
     * @var Api
     */
    private $pusherApi;

    public function push(){


//        $api = new Api(
//        // webman下可以直接使用config获取配置，非webman环境需要手动写入相应配置
//            'http://127.0.0.1:3232',
//            config('plugin.webman.push.app.app_key'),
//            config('plugin.webman.push.app.app_secret')
//        );
// 给订阅 user-1 的所有客户端推送 message 事件的消息
        $bool = $this->pusherApi->trigger('user-1', 'message', [
            'from_uid' => 2,
            'content'  => '你好，这个是消息内容'
        ]);
        return $this->success("推送成功：".$bool);
    }

    public function priPush(Request $request){
        $userId = $request->get('user_id',"12018910349984");
        $event = $request->get('event',"message");
        $event = $request->get('msg',"message");
        $api = new Api(
        // webman下可以直接使用config获取配置，非webman环境需要手动写入相应配置
            'http://127.0.0.1:3232',
            config('plugin.webman.push.app.app_key'),
            config('plugin.webman.push.app.app_secret')
        );
// 给订阅 user-1 的所有客户端推送 message 事件的消息
        $channel = 'private-user-'.$userId;
        var_dump("推送的通道：".$channel);
        $bool = $api->trigger($channel, $event, [
            'from_uid' => 2,
            'content'  => '你好，这个是私人授权通过消息内容'
        ]);
        return $this->success("推送成功：".$bool);
    }

}