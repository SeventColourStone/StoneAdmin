<?php


namespace plugin\stone\nyuwa\crontab;

/**
 * Class NyuwaClient
 * @package nyuwa\crontab
 */
class NyuwaClient
{

    private $client;
    protected static $instance = null;

    public function __construct()
    {
        $this->client = stream_socket_client('tcp://' . config('app.task.listen'));
    }

    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function request(array $param)
    {
        fwrite($this->client, json_encode($param) . "\n"); // text协议末尾有个换行符"\n"
        $result = fgets($this->client, 10240000);
        return json_decode($result);
    }
}