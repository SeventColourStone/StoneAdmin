<?php

declare(strict_types = 1);
namespace plugin\stone\app\service\system;



use plugin\stone\app\mapper\system\SystemOperLogMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;

class SystemOperLogService extends AbstractService
{
    /**
     * @var SystemOperLogMapper
     */
    public  $mapper;


    public function __construct(SystemOperLogMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}