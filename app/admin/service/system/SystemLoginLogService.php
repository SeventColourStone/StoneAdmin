<?php

declare(strict_types=1);
namespace app\admin\service\system;


use app\admin\mapper\system\SystemLoginLogMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

/**
 * 登录日志业务
 * Class SystemLoginLogService
 * @package App\System\Service
 */
class SystemLoginLogService extends AbstractService
{
    /**
     * @Inject
     * @var SystemLoginLogMapper
     */
    public $mapper;

}