<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemRoleMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemRoleService extends AbstractService
{
    /**
     * @Inject
     * @var SystemRoleMapper
     */
    public $mapper;

}
