<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemUserMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemUserService extends AbstractService
{
    /**
     * @Inject
     * @var SystemUserMapper
     */
    public $mapper;

}
