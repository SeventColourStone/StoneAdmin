<?php


namespace plugin\stone\app\controller\system;


use plugin\stone\app\service\setting\ServerMonitorService;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;

class ServerController extends NyuwaController
{
    #[Inject]
    protected ServerMonitorService $service;

    /**
     * 获取服务器信息
     */
    #[GetMapping("monitor"), Permission("system:monitor:server")]
    public function monitor(): NyuwaResponse
    {
//        $channel = new Channel();
//        co(function () use($channel) {
//            $channel->push(['cpu' => $this->service->getCpuInfo()]);
//        });
//        co(function () use($channel) {
//            $channel->push(['net' => $this->service->getNetInfo()]);
//        });
//        $info = ['cpu' => $this->service->getCpuInfo(),'net' => $this->service->getNetInfo()];
//        var_dump($info);
        $linfo = new \Linfo\Linfo;
        $parser = $linfo->getParser();


        var_dump($parser->getCPU()); // and a whole lot more

//        $result = $channel->pop();

        return $this->success([
//            'cpu' => $info['cpu'] ,
//            'memory' => $this->service->getMemInfo(),
//            'phpenv' => $this->service->getPhpAndEnvInfo(),
//            'net'    => $info['net'],
//            'disk'   => $this->service->getDiskInfo()
        ]);
    }

}