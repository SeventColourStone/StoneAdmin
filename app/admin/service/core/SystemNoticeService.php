<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemNoticeMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemNoticeService extends AbstractService
{
    /**
     * @Inject
     * @var SystemNoticeMapper
     */
    public $mapper;

}
