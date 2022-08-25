<?php

declare(strict_types=1);

namespace app\admin\service\system;


use app\admin\mapper\system\SystemDictTypeMapper;
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
