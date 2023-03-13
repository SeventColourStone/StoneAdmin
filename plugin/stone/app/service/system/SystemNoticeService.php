<?php

declare(strict_types=1);

namespace plugin\stone\app\service\system;


use plugin\stone\app\mapper\system\SystemNoticeMapper;
use DI\Attribute\Inject;
use plugin\stone\nyuwa\abstracts\AbstractService;

class SystemNoticeService extends AbstractService
{
    /**
     * @var SystemNoticeMapper
     */
    public  $mapper;


    public function __construct(SystemNoticeMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}
