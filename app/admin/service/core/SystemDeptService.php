<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemDeptMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemDeptService extends AbstractService
{
    /**
     * @Inject
     * @var SystemDeptMapper
     */
    public $mapper;

}
