<?php

declare(strict_types=1);

namespace plugin\stone\app\service\setting;


use plugin\stone\app\mapper\setting\SettingCrontabMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;
use plugin\stone\nyuwa\crontab\NyuwaClient;
use support\Redis;

class SettingCrontabService extends AbstractService
{
    public $mapper;

    public function __construct(SettingCrontabMapper $mapper)
    {
        $this->mapper = $mapper;
    }
    /**
     * 保存
     * @param array $data
     * @return int
     */
    public function save(array $data): string
    {
        $id = parent::save($data);
        Redis::del( 'crontab');

        return $id;
    }

    /**
     * 更新
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        $res = parent::update($id, $data);
        Redis::del( 'crontab');

        return $res;
    }

    /**
     * 删除
     * @param string $ids
     * @return bool
     */
    public function delete(string $ids): bool
    {
        $res = parent::delete($ids);
        Redis::del( 'crontab');

        return $res;
    }

    /**
     * 立即执行一次定时任务
     * @param $id
     * @return bool|null
     */
    public function run($id): ?bool
    {

        $param = [
//            'method' => 'crontabIndex',//计划任务列表
            'method' => 'crontabRunOne',//计划任务运行一次
            'args'   => $id//参数
        ];
        $result= NyuwaClient::instance()->request($param);
        return true;
    }
}
