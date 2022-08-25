<?php


namespace app\admin\controller\api;


use app\admin\model\system\SystemDictField;
use app\admin\model\system\SystemMenu;
use app\admin\model\system\SystemUser;
use app\admin\service\system\SystemUserService;
use DI\Annotation\Inject;
use FastRoute\Route;
use FastRoute\RouteCollector;
use Illuminate\Support\Facades\Event;
use Lcobucci\JWT\Token\Plain;
use nyuwa\auth\constant\JWTConstant as JWTConstantAlias;
use nyuwa\event\TestEvent;
use nyuwa\exception\NyuwaException;
use nyuwa\helper\LoginUser;
use nyuwa\NyuwaController;
use nyuwa\traits\GencodeTrait;
use support\Container;
use support\Db;
use W7\Validate\Exception\ValidateException;
use W7\Validate\Validate;
use Webman\Config;
use Webman\Push\Api;
use Webman\RedisQueue\Client;

/**
 * Class TestController
 * @package app\admin\controller\api
 */
class TestController extends NyuwaController
{
    use GencodeTrait;

    /**
     * @Inject
     * @var Api
     */
    private $pusherApi;

    public function test(){
// 给订阅 user-1 的所有客户端推送 message 事件的消息
        $bool = $this->pusherApi->trigger('private-user-16128263327904', 'message', [
            'from_uid' => 2,
            'content'  => '你好，这个是消息内容'
        ]);
        return $this->success("推送成功：".$bool);
    }

    /**
     * @return \nyuwa\NyuwaResponse
     */
    public function test1(){
        $table = "system_user";
//        SystemDictField::query()->where()
//        $sceneConfig = Config::get(JWTConstantAlias::CONFIG_NAME);


        // 队列名
        $queue = 'test';
        // 数据，可以直接传数组，无需序列化
        $data = ['to' => 'tom@gmail.com', 'content' => 'hello'];
        // 投递消息
        Client::send($queue, $data);
        $tableBaseInfo = $this->getTableBaseInfo($table);
        $tableDetailInfo = $this->getTableDetailInfo($table);
        if (empty($tableBaseInfo->TABLE_COMMENT)){
            throw new NyuwaException("生成的curd表需要添加注释");
        }

        $tableCamelCase = nyuwa_camelize($table);
        $tableSmallCamelCase = nyuwa_camelize($table,'_',0);
        $tableUnderScoreCase = nyuwa_uncamelize($table);
        $tableCenterScoreCase = nyuwa_uncamelize($tableCamelCase,"-");
        //所有文件生成的基础参数
        $basicConfigInfo = [
            "tableComment"=>$tableBaseInfo->TABLE_COMMENT, //表注释
            "tableUnderScoreCase"=>$tableUnderScoreCase,  // 表名下划线格式
            "tableCenterScoreCase"=>$tableCenterScoreCase, //表名中划线式，用作前端id名
            "tableCamelCase"=>$tableCamelCase, //表名大驼峰式
            "tableSmallCamelCase"=>$tableSmallCamelCase, //表名小驼峰式
        ];
        [$tableSearchPhpColString,$tableSearchHtmlColString] = $this->getMapperParamsInfo("system_menu",$tableDetailInfo,$basicConfigInfo);
        return $this->success([$tableSearchPhpColString,$tableSearchHtmlColString]);
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
