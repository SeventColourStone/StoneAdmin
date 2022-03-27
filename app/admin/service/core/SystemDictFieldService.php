<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemDictFieldMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemDictFieldService extends AbstractService
{
    /**
     * @Inject
     * @var SystemDictFieldMapper
     */
    public $mapper;

}
