<?php


namespace nyuwa\listener;


use app\bizMonitor\service\PlatformBizInfoService;
use DI\Annotation\Inject;
use nyuwa\event\BizInfoEvent;

class BizInfoListener
{

    /**
     * @Inject
     * @var PlatformBizInfoService
     */
    private $platformBizInfoService;
    /**
     * 创建事件监听器。
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 处理事件。
     *
     * @return void
     */
    public function handle(BizInfoEvent $event)
    {
        // 使用 $event->order 来访问订单 ...
//        var_dump($this->platformBizInfoService);
        $this->platformBizInfoService = nyuwa_app(PlatformBizInfoService::class);
        $this->platformBizInfoService->save([
           "platform_id" => $event->getPlatformId(),
           "biz_sn" => $event->getKeySn(),
           "type" => $event->getType(),
           "biz_info" => $event->getContent(),
        ]);
    }
}