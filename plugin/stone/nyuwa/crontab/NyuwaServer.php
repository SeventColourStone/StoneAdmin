<?php
declare (strict_types=1);

namespace plugin\stone\nyuwa\crontab;

use plugin\stone\service\core\SettingCrontabLogService;
use plugin\stone\service\core\SettingCrontabService;
use DI\Attribute\Inject;
use support\Db;
use support\Log;
use Webman\Container;
use Workerman\Connection\TcpConnection;
use Workerman\Crontab\Crontab;
use Workerman\Worker;

/**
 * 注意：定时器开始、暂停、重起 都是在下一分钟开始执行
 */
class NyuwaServer
{
    const FORBIDDEN_STATUS = '1';

    const NORMAL_STATUS = '0';

    // 命令任务
    public const COMMAND_CRONTAB = '1';
    // 类任务
    public const CLASS_CRONTAB = '2';
    // URL任务
    public const URL_CRONTAB = '3';
    // EVAL 任务
    public const EVAL_CRONTAB = '4';
    //shell 任务
    public const SHELL_CRONTAB = '5';

    /**
     * @var SettingCrontabService
     */
    #[Inject]
    protected $settingCrontabService;


    /**
     * @var SettingCrontabLogService
     */
    #[Inject]
    protected $settingCrontabLogService;


    /**
     * @var Worker
     */
    private $worker;

    /**
     * 调试模式
     * @var bool
     */
    private $debug = false;

    /**
     * 任务进程池
     * @var Crontab[] array
     */
    private $crontabPool = [];

    /**
     * 定时任务表
     * @var string
     */
    private $crontabTable;

    /**
     * 定时任务日志表
     * @var string
     */
    private $crontabLogTable;

    public function __construct()
    {
    }


    public function onWorkerStart($worker)
    {
        $config                = config('app.task');
        $this->debug           = $config['debug'];
        $this->crontabTable    = $config['crontab_table'];
        $this->crontabLogTable = $config['crontab_table_log'];
        $this->worker          = $worker;

        $this->crontabInit();
    }

    /**
     * 当客户端与Workman建立连接时(TCP三次握手完成后)触发的回调函数
     * 每个连接只会触发一次onConnect回调
     * 此时客户端还没有发来任何数据
     * 由于udp是无连接的，所以当使用udp时不会触发onConnect回调，也不会触发onClose回调
     * @param TcpConnection $connection
     */
    public function onConnect(TcpConnection $connection)
    {
//        $this->checkCrontabTables();
    }


    public function onMessage(TcpConnection $connection, $data)
    {
        $data   = json_decode($data, true);
        $method = $data['method'];
        $args   = $data['args'];
        $connection->send(call_user_func([$this, $method], $args));
    }


    /**
     * 初始化定时任务
     * @return void
     */
    private function crontabInit(): void
    {
        $ids = $this->settingCrontabService->mapper->getModel()->newQuery()->where('status', self::NORMAL_STATUS)
            ->pluck('id');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $this->crontabRun($id);
            }
        }
    }

    private function crontabRunOne($id){
        $data = $this->settingCrontabService->read((string)$id)->toArray();
        if ($data['status'] == self::NORMAL_STATUS){
        }
        $data['code'] = $data['id'];
        $run = true;
        if ($run){
            $data['name'] = "RUN-ONE-".$data['name'];
//            $data['rule'] = "*/1 * * * * *";
            $data['singleton'] = 0;
            $data['code'] = "RUN-ONE-".$data['id'];
        }
        $this->debug && $this->writeProcessLn("立即运行一次 ".json_encode($data,JSON_UNESCAPED_UNICODE),"OnlyOne");
        if (!empty($data)) {
            switch ($data['type']) {
                case self::COMMAND_CRONTAB:
                    $this->runCommandCrontab($data);
                    break;
                case self::CLASS_CRONTAB:
                    $this->runClasscRontab($data);
                    break;
                case self::URL_CRONTAB:
                    $this->runUrlCrontab($data);
                    break;
                case self::SHELL_CRONTAB:
                    $this->runShellCrontab($data);
                    break;
                case self::EVAL_CRONTAB:
                    $this->runEvalCrontab($data);
                    break;
            }
        }

    }

    private function runCommandCrontab($data){
        $time      = time();
        $date = date("Y-m-d H:i:s");
        $parameter = $data['parameter'] ?: '{}';
        $startTime = microtime(true);
        $code      = 0;
        $result    = true;
        try {
            if (strpos($data['target'], 'php stone') !== false) {
                $command = $data['target'];
            } else {
                $command = "php stone " . $data['target'];
            }
            $exception = shell_exec($command);
        } catch (\Throwable $e) {
            $result    = false;
            $code      = 1;
            $exception = $e->getMessage();
        }

        $this->runInSingleton($data);

        $this->debug && $this->writeln('执行定时器任务#' . $data['code'] . ' ' . $data['rule'] . ' ' . $data['target'], $result);
        $endTime = microtime(true);
//                            Db::update("UPDATE {$this->crontabTable} SET running_times = running_times + 1, last_running_time = {$date} WHERE id = {$data['id']}");
        $this->settingCrontabLogService->save([
            'crontab_id'   => $data['id'],
            'name'   => $data['name'],
            'target'       => $data['target'],
            'parameter'    => $parameter,
            'exception_info'    => $exception,
            'status'  => $code,
//                                'running_time' => round($endTime - $startTime, 6),
            'created_at'  => $date,
        ]);
    }
    private function runClasscRontab($data){
        $time       = time();
        $date = date("Y-m-d H:i:s");
        $class      = trim($data['target']);
        $method     = 'execute';
        $parameters = $data['parameter'] ?: null;
        $startTime  = microtime(true);
        if ($class && class_exists($class) && method_exists($class, $method)) {
            try {
                $result   = true;
                $code     = 0;
                $instance = Container::get($class);
                if ($parameters && is_array($parameters)) {
                    $res = $instance->{$method}(...$parameters);
                } else {
                    $res = $instance->{$method}();
                }
            } catch (\Throwable $throwable) {
                $result = false;
                $code   = 1;
            }
            $exception = isset($throwable) ? $throwable->getMessage() : $res;
        }

        $this->runInSingleton($data);
        $this->debug && $this->writeln('执行定时器任务#' . $data['code'] . ' ' . $data['rule'] . ' ' . $data['target'], $result);
        $endTime = microtime(true);
//                            Db::update("UPDATE {$this->crontabTable} SET running_times = running_times + 1, last_running_time = {$date} WHERE id = {$data['id']}");
        $this->settingCrontabLogService->save([
            'crontab_id'   => $data['id'],
            'name'   => $data['name'],
            'target'       => $data['target'],
            'parameter'    => $parameters,
            'exception_info'    => $exception ?? '',
            'status'  => $code,
//                                'running_time' => round($endTime - $startTime, 6),
            'created_at'  => $date,
//                                'update_time'  => $time,
        ]);
    }

    private function runUrlCrontab($data){
        $time      = time();
        $date = date("Y-m-d H:i:s");
        $url       = trim($data['target']);
        $startTime = microtime(true);
        $client    = new \GuzzleHttp\Client();
        try {
            $response = $client->get($url);
            $result   = $response->getStatusCode() === 200;
            $code     = 0;
        } catch (\Throwable $throwable) {
            $result    = false;
            $code      = 1;
            $exception = $throwable->getMessage();
        }

        $this->runInSingleton($data);

        $this->debug && $this->writeln('执行定时器任务#' . $data['code'] . ' ' . $data['rule'] . ' ' . $data['target'], $result);
        $endTime = microtime(true);
//                            Db::update("UPDATE {$this->crontabTable} SET running_times = running_times + 1, last_running_time = {$date} WHERE id = {$data['id']}");
        $this->settingCrontabLogService->save([
            'crontab_id'   => $data['id'],
            'name'   => $data['name'],
            'target'       => $data['target'],
            'parameter'    => $data['parameter'],
            'exception_info'    => $exception ?? '',
            'status'  => $code,
//                                'running_time' => round($endTime - $startTime, 6),
            'created_at'  => $date,
//                                'update_time'  => $time,
        ]);
    }
    private function runShellCrontab($data){
        $time      = time();
        $date = date("Y-m-d H:i:s");
        $parameter = $data['parameter'] ?: '';
        $startTime = microtime(true);
        $code      = 0;
        $result    = true;
        try {
            $exception = shell_exec($data['target']);
        } catch (\Throwable $e) {
            $result    = false;
            $code      = 1;
            $exception = $e->getMessage();
        }

        $this->runInSingleton($data);

        $this->debug && $this->writeln('执行定时器任务#' . $data['code'] . ' ' . $data['rule'] . ' ' . $data['target'], $result);
        $endTime = microtime(true);
//                            Db::update("UPDATE {$this->crontabTable} SET running_times = running_times + 1, last_running_time = {$date} WHERE id = {$data['id']}");
        $this->settingCrontabLogService->save([
            'crontab_id'   => $data['id'],
            'name'   => $data['name'],
            'target'       => $data['target'],
            'parameter'    => $parameter,
            'exception_info'    => $exception,
            'status'  => $code,
//                                'running_time' => round($endTime - $startTime, 6),
            'created_at'  => $date,
//                                'update_time'  => $time,
        ]);
    }
    private function runEvalCrontab($data){
        $time      = time();
        $date = date("Y-m-d H:i:s");
        $startTime = microtime(true);
        $result    = true;
        $code      = 0;
        try {
            eval($data['target']);
        } catch (\Throwable $throwable) {
            $result    = false;
            $code      = 1;
            $exception = $throwable->getMessage();
        }

        $this->runInSingleton($data);

        $this->debug && $this->writeln('执行定时器任务#' . $data['code'] . ' ' . $data['rule'] . ' ' . $data['target'], $result);
        $endTime = microtime(true);
//                            Db::update("UPDATE {$this->crontabTable} SET running_times = running_times + 1, last_running_time = {$date} WHERE id = {$data['id']}");

//                            $row = $this->settingCrontabService->mapper->getModel()->increment('running_times', 1, ['id' => $data['id']]);
        $this->settingCrontabLogService->save([
            'crontab_id'   => $data['id'],
            'name'   => $data['name'],
            'target'       => $data['target'],
            'parameter'    => $data['parameter'],
            'exception_info'    => $exception ?? '',
            'status'  => $code,
//                                'running_time' => round($endTime - $startTime, 6),
            'created_at'  => $date,
//                                'update_time'  => $time,
        ]);
    }

    /**
     * 创建定时器
     * @param $id
     * @param $run 立即运行一次
     */
    private function crontabRun($id, $run = false)
    {
        $data = $this->settingCrontabService->read((string)$id)->toArray();
        if ($data['status'] == self::NORMAL_STATUS){
        }
        $data['code'] = $data['id'];
        $this->debug && $this->writeProcessLn("定时器执行：".json_encode($data,JSON_UNESCAPED_UNICODE),"Create");
        if (!empty($data)) {
            switch ($data['type']) {
                case self::COMMAND_CRONTAB:
                    $this->crontabPool[$data['code']] = [
                        'id'          => $data['id'],
                        'target'      => $data['target'],
                        'rule'        => $data['rule'],
                        'parameter'   => $data['parameter'],
                        'singleton'   => $data['singleton'],
                        'create_time' => date('Y-m-d H:i:s'),
                        'crontab'     => new Crontab($data['rule'], function () use ($data) {
                            $this->runCommandCrontab($data);
                        })
                    ];
                    break;
                case self::CLASS_CRONTAB:
                    $this->crontabPool[$data['code']] = [
                        'id'          => $data['id'],
                        'target'      => $data['target'],
                        'rule'        => $data['rule'],
                        'parameter'   => $data['parameter'],
                        'singleton'   => $data['singleton'],
                        'create_time' => date('Y-m-d H:i:s'),
                        'crontab'     => new Crontab($data['rule'], function () use ($data) {
                            $this->runClasscRontab($data);
                        })
                    ];
                    break;
                case self::URL_CRONTAB:
                    $this->crontabPool[$data['code']] = [
                        'id'          => $data['id'],
                        'target'      => $data['target'],
                        'rule'        => $data['rule'],
                        'parameter'   => $data['parameter'],
                        'singleton'   => $data['singleton'],
                        'create_time' => date('Y-m-d H:i:s'),
                        'crontab'     => new Crontab($data['rule'], function () use ($data) {
                            $this->runUrlCrontab($data);
                        })
                    ];
                    break;
                case self::SHELL_CRONTAB:
                    $this->crontabPool[$data['code']] = [
                        'id'          => $data['id'],
                        'target'      => $data['target'],
                        'rule'        => $data['rule'],
                        'parameter'   => $data['parameter'],
                        'singleton'   => $data['singleton'],
                        'create_time' => date('Y-m-d H:i:s'),
                        'crontab'     => new Crontab($data['rule'], function () use ($data) {
                            $this->runShellCrontab($data);

                        })
                    ];
                    break;
                case self::EVAL_CRONTAB:
                    $this->crontabPool[$data['code']] = [
                        'id'          => $data['id'],
                        'target'      => $data['target'],
                        'rule'        => $data['rule'],
                        'parameter'   => $data['parameter'],
                        'singleton'   => $data['singleton'],
                        'create_time' => date('Y-m-d H:i:s'),
                        'crontab'     => new Crontab($data['rule'], function () use ($data) {
                            $this->runEvalCrontab($data);
                        })
                    ];
                    break;
            }
        }
    }


    /**
     * 是否单次
     * @param $crontab
     * @return void
     */
    private function runInSingleton($crontab)
    {
        if ($crontab['singleton'] == 0 && isset($this->crontabPool[$crontab['code']])) {
            //是定时器才销毁。
            $this->debug && $this->writeProcessLn("定时器销毁","Destroy");
            $this->crontabPool[$crontab['code']]['crontab']->destroy();
        }
    }


    /**
     * 创建定时任务
     * @param array $param
     * @return string
     */
    private function crontabCreate(array $param): string
    {
        $param['create_time'] = $param['update_time'] = time();
        $id                   = Db::table($this->crontabTable)
            ->insertGetId($param);
        $id && $this->crontabRun($id);

        return json_encode(['code' => 200, 'msg' => 'ok', 'data' => ['code' => (bool)$id]]);
    }

    /**
     * 修改定时器
     * @param array $param
     * @return string
     */
    private function crontabUpdate(array $param): string
    {
//        $row = Db::table($this->crontabTable)
//            ->where('id', $param['id'])
//            ->update($param);
        $row = $this->settingCrontabService->update($param['id'],$param);

        if (isset($this->crontabPool[$param['id']])) {
            $this->crontabPool[$param['id']]['crontab']->destroy();
            unset($this->crontabPool[$param['id']]);
        }
        if ($param['status'] == self::NORMAL_STATUS) {
            $this->crontabRun($param['id']);
        }

        return json_encode(['code' => 200, 'msg' => 'ok', 'data' => ['code' => (bool)$row]]);

    }


    /**
     * 清除定时任务
     * @param array $param
     * @return string
     */
    private function crontabDelete(array $param): string
    {
        if ($id = $param['id']) {
            $ids = explode(',', (string)$id);

            foreach ($ids as $item) {
                if (isset($this->crontabPool[$item])) {
                    $this->crontabPool[$item]['crontab']->destroy();
                    unset($this->crontabPool[$item]);
                }
            }

            $bool = $this->settingCrontabService->realDelete($id);

            return json_encode(['code' => 200, 'msg' => 'ok', 'data' => ['code' => $bool]]);
        }

        return json_encode(['code' => 200, 'msg' => 'ok', 'data' => ['code' => true]]);
    }

    /**
     * 重启定时任务
     * @param array $param
     * @return string
     */
    private function crontabReload(array $param): string
    {
        $ids = explode(',', (string)$param['id']);

        foreach ($ids as $id) {
            if (isset($this->crontabPool[$id])) {
                $this->crontabPool[$id]['crontab']->destroy();
                unset($this->crontabPool[$id]);
            }
            Db::table($this->crontabTable)
                ->where('id', $id)
                ->update(['status' => self::NORMAL_STATUS]);
            $this->crontabRun($id);
        }

        return json_encode(['code' => 200, 'msg' => 'ok', 'data' => ['code' => true]]);
    }


    /**
     * 输出日志
     * @param $msg
     * @param bool $isSuccess
     */
    private function writeln($msg, bool $isSuccess = false)
    {
        echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . ($isSuccess ? " [Ok] " : " [Fail] ") . PHP_EOL;
    }

    /**
     * 输出日志
     * @param $msg
     * @param bool $isSuccess
     */
    private function writeProcessLn($msg, $process)
    {
        echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . (" [$process] ") . PHP_EOL;
    }
}