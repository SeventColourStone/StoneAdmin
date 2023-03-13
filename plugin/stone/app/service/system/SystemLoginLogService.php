<?php

declare(strict_types=1);
namespace plugin\stone\app\service\system;


use plugin\stone\app\mapper\system\SystemLoginLogMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;

/**
 * 登录日志业务
 * Class SystemLoginLogService
 * @package App\System\Service
 */
class SystemLoginLogService extends AbstractService
{
    /**
     * @var SystemLoginLogMapper
     */
    public $mapper;


    public function __construct(SystemLoginLogMapper $mapper)
    {
        $this->mapper = $mapper;
    }

}