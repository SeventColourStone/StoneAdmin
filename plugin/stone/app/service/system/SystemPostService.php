<?php

declare(strict_types=1);

namespace plugin\stone\app\service\system;


use plugin\stone\app\mapper\system\SystemPostMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;

class SystemPostService extends AbstractService
{
    /**
     * @var SystemPostMapper
     */
    public $mapper;


    public function __construct(SystemPostMapper $mapper)
    {
        $this->mapper = $mapper;
    }

}
