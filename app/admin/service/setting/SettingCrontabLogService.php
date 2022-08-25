<?php

declare(strict_types=1);

namespace app\admin\service\setting;


use app\admin\mapper\setting\SettingCrontabLogMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SettingCrontabLogService extends AbstractService
{
    /**
     * @Inject
     * @var SettingCrontabLogMapper
     */
    public $mapper;

}
