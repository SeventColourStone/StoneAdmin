<?php

declare(strict_types=1);

namespace plugin\stone\app\service\setting;


use plugin\stone\app\mapper\setting\SettingCrontabLogMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;

class SettingCrontabLogService extends AbstractService
{
    public $mapper;

    public function __construct(SettingCrontabLogMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}
