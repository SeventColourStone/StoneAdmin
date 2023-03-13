<?php


/**
 * 对一些框架级的方法做一下包装，减低框架级别依赖
 */

use Godruoyi\Snowflake\Snowflake;
use plugin\stone\nyuwa\helper\LoginUser;
use plugin\stone\nyuwa\NyuwaCollection;
use plugin\stone\nyuwa\vo\QueueMessageVo;
use support\Container;
use plugin\stone\nyuwa\Event;
use Symfony\Component\Finder\Finder;
use Webman\App;
use Webman\Http\Request;
use Webman\Route;

if (! function_exists('nyuwa_user')) {
    /**
     * 获取当前登录用户实例
     * @param string $scene
     * @return LoginUser
     */
    function nyuwa_user(string $scene = 'default')
    {
        return new LoginUser($scene);
    }

}



if (! function_exists('nyuwa_app')) {
    /**
     * 获取一个实例化的对象
     * @param null $abstract
     * @return mixed
     */
    function nyuwa_app($abstract = null)
    {
        return Container::get($abstract);
    }
}

if (!function_exists("nyuwa_dd")){
    function nyuwa_dd($data,$var_dump = false){

        ob_start();
        if ($var_dump){
            var_dump($data);
        }else{
            print_r($data);
        }
        $ob_get_contents = ob_get_contents();
        ob_flush();
        flush();

        return response($ob_get_contents);
    }
}

if (! function_exists('nyuwa_request')) {
    /**
     * 获取一个实例化请求对象
     * @return Request
     */
    function nyuwa_request()
    {
        return App::request();
    }
}


if (! function_exists('nyuwa_trans')) {
    /**
     * 翻译
     * @return string
     */
    function nyuwa_trans(string $content , array $parameters = [],string $locale = 'zh_CN')
    {
        $info = explode(".",$content);
        if (count($info)==2){
            return trans($info[1], [], $info[0], $locale);
        }
        return trans($content);
    }
}
if (! function_exists('nyuwa_event')) {
    /**
     * 事件
     * @param $event
     */
    function nyuwa_event($event)
    {
        Event::dispatch($event);
    }
}



if (! function_exists('nyuwa_collect')) {
    /**
     * 创建一个集合类
     * @param null|mixed $value
     */
    function nyuwa_collect($value = null)
    {
        return new NyuwaCollection($value);
    }
}



if (! function_exists('nyuwa_camelize')) {
    /**
     * 下划线转驼峰
     * 思路:
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     * type 1 为大驼峰 2为小驼峰
     */
    function nyuwa_camelize($uncamelized_words, $separator = '_', $type = 1)
    {
        $uncamelized_words = ($type == 1 ? "" : $separator) . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }
}



if (! function_exists('nyuwa_uncamelize')) {
    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     */
    function nyuwa_uncamelize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}



if (!function_exists("nyuwa_is_json")){
    /**
     * 判断字符串是否为 Json 格式
     *
     * @param string $data Json 字符串
     * @param bool $assoc 是否返回关联数组。默认返回对象
     *
     * @return array|bool|object 成功返回转换后的对象或数组，失败返回 false
     */
    function nyuwa_is_json(string $data = '', bool $assoc = true){
        $data = json_decode($data, $assoc);
        if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
            return $data;
        }
        return false;
    }
}


if (!function_exists("nyuwa_files")){
    /**
     * Get an array of all files in a directory.
     */
    function nyuwa_files(string $directory, bool $hidden = false){
        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(! $hidden)->in($directory)->depth(0)->sortByName(),
            false
        );
    }
}


if (!function_exists("nyuwa_directories")) {
    /**
     * Get all of the directories within a given directory.
     */
    function nyuwa_directories(string $directory): array
    {
        $directories = [];

        foreach (Finder::create()->in($directory)->directories()->depth(0)->sortByName() as $dir) {
            $directories[] = $dir->getPathname();
        }

        return $directories;
    }
}



if (! function_exists('push_queue_message')) {
    /**
     * 推送消息到队列
     * @param QueueMessageVo $message
     * @param array $receiveUsers
     * @return int 消息ID，若失败返回 -1
     * @throws Throwable
     */
    function push_queue_message(QueueMessageVo $message, array $receiveUsers = []): int
    {
        return container()
            ->get(\App\System\Service\SystemQueueLogService::class)
            ->pushMessage($message, $receiveUsers);
    }
}

if (! function_exists('add_queue')) {
    /**
     * 添加任务到队列
     * @param \App\System\Vo\AmqpQueueVo $amqpQueueVo
     * @return bool
     * @throws Throwable
     */
    function add_queue(\App\System\Vo\AmqpQueueVo $amqpQueueVo): bool
    {
        return container()
            ->get(\App\System\Service\SystemQueueLogService::class)
            ->addQueue($amqpQueueVo);
    }
}


if (! function_exists('snowflake_id')) {
    /**
     * 生成雪花ID
     * @return String
     */
    function snowflake_id($dataCenter = 1): String
    {
        $workerId = App::worker()->id??1;
        $snowflake = Container::make(Snowflake::class,[$dataCenter,$workerId]);
        return (string)$snowflake->id();
    }
}

if (! function_exists('format_size')) {
    /**
     * 格式化大小
     * @param int $size
     * @return string
     */
    function format_size(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $index = 0;
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
            $index = $i;
        }
        return round($size, 2) . $units[$index];
    }
}
