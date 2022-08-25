<?php

declare(strict_types=1);

namespace app\admin\service\setting;


use app\admin\mapper\setting\SettingCrontabMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;
use nyuwa\crontab\NyuwaClient;
use support\Redis;

class SettingCrontabService extends AbstractService
{
    /**
     * @Inject
     * @var SettingCrontabMapper
     */
    public $mapper;


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
