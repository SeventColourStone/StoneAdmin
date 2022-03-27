<?php


namespace app\admin\controller\api;


use app\admin\model\system\SystemDictField;
use app\admin\model\system\SystemMenu;
use app\admin\model\system\SystemUser;
use app\admin\service\system\SystemUserService;
use FastRoute\Route;
use FastRoute\RouteCollector;
use Illuminate\Support\Facades\Event;
use Lcobucci\JWT\Token\Plain;
use nyuwa\auth\constant\JWTConstant as JWTConstantAlias;
use nyuwa\event\TestEvent;
use nyuwa\helper\LoginUser;
use nyuwa\NyuwaController;
use support\Container;
use support\Db;
use W7\Validate\Exception\ValidateException;
use W7\Validate\Validate;
use Webman\Config;
use Webman\Push\Api;

/**
 * Class TestController
 * @package app\admin\controller\api
 */
class TestController extends NyuwaController
{
    /**
     * @return \nyuwa\NyuwaResponse
     */
    public function test(){
        $table = "system_user";
//        SystemDictField::query()->where()
//        $sceneConfig = Config::get(JWTConstantAlias::CONFIG_NAME);
        $sceneConfig = Config::get("auth.config.scene.application");
        return $this->success($sceneConfig);
    }


    public function push(){
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

//        $info = SystemUser::query()->first();
//        /**
//         * @var LoginUser
//         */
//        $loginUser = nyuwa_app(LoginUser::class);
//        $token = $loginUser->getToken($info->toArray());
//        nyuwa_event(new TestEvent("哈哈哈"));
//
//        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzYyMDhhZjFmOGVlYmIxLjYyOTk2NjUwIiwiaWF0IjoxNjQ0NzM2Mjg3LjU5MjY5NywibmJmIjoxNjQ0NzM2Mjg3LjU5MjY5NywiZXhwIjoxNjQ0NzQzNDg3LjU5MjY5NywiaWQiOjEyMDE4OTEwMzQ5OTg0LCJ1c2VybmFtZSI6InN1cGVyQWRtaW4iLCJwYXNzd29yZCI6IiQyeSQxMCREMExteURYQm9rTm9CN25jcG1LN0hPS0N3QWpFRng4YW1MYXE0OG5qOGNCcWFRS3V2M0VFdSIsInVzZXJfdHlwZSI6IjEwMCIsIm5pY2tuYW1lIjoi5Yib5aeL5Lq6IiwicGhvbmUiOiIxNjg1ODg4ODk4OCIsImVtYWlsIjoiYWRtaW5AYWRtaW5taW5lLmNvbSIsImF2YXRhciI6bnVsbCwic2lnbmVkIjpudWxsLCJkYXNoYm9hcmQiOiJzdGF0aXN0aWNzIiwiZGVwdF9pZCI6MTIwMTkzMDY4MzkyMDAsInN0YXR1cyI6IjAiLCJsb2dpbl9pcCI6IjEyNy4wLjAuMSIsImxvZ2luX3RpbWUiOiIyMDIyLTAyLTA4IDEwOjQ5OjU0IiwiYmFja2VuZF9zZXR0aW5nIjpudWxsLCJjcmVhdGVkX2J5IjowLCJ1cGRhdGVkX2J5IjowLCJjcmVhdGVkX2F0IjoiMjAyMi0wMi0wN1QwOToyNDoxNC4wMDAwMDBaIiwidXBkYXRlZF9hdCI6IjIwMjItMDItMDhUMDI6NDk6NTQuMDAwMDAwWiIsInJlbWFyayI6bnVsbCwiand0X3NjZW5lIjoiZGVmYXVsdCJ9.YYYH9fbM9rXTMQ59kXcYSTqMeGCUe-17IdpCs_R2A9E";
//        $check = $loginUser->check($token);
//        var_dump($check);
//
//        /**
//         * @var JWT
//         */
////        $jwt = $loginUser->getJwt();
////        $info = $jwt->getParserData($token);
