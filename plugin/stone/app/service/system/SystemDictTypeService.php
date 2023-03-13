<?php

declare(strict_types=1);

namespace plugin\stone\app\service\system;


use plugin\stone\app\mapper\system\SystemDictTypeMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;

class SystemDictTypeService extends AbstractService
{
    /**
     * @var SystemDictTypeMapper
     */
    public $mapper;


    public function __construct(SystemDictTypeMapper $mapper)
    {
        $this->mapper = $mapper;
    }

}
