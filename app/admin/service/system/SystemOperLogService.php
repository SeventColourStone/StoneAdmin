<?php

declare(strict_types = 1);
namespace app\admin\service\system;



use app\admin\mapper\system\SystemOperLogMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemOperLogService extends AbstractService
{
    /**
     * @Inject
     * @var SystemOperLogMapper
     */
    public $mapper;
}