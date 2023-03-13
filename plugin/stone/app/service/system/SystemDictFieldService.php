<?php

declare(strict_types=1);

namespace plugin\stone\app\service\system;


use plugin\stone\app\mapper\system\SystemDictFieldMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;

class SystemDictFieldService extends AbstractService
{
    /**
     * @var SystemDictFieldMapper
     */
    public  $mapper;


    public function __construct(SystemDictFieldMapper $mapper)
    {
        $this->mapper = $mapper;
    }

}
