<?php

declare(strict_types=1);

namespace app\admin\service\system;


use app\admin\mapper\system\SystemNoticeMapper;
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
