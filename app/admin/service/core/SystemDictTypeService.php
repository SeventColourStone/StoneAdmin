<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemDictTypeMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemDictTypeService extends AbstractService
{
    /**
     * @Inject
     * @var SystemDictTypeMapper
     */
    public $mapper;

}
