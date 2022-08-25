<?php

declare(strict_types=1);

namespace app\admin\service\system;


use app\admin\mapper\system\SystemPostMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemPostService extends AbstractService
{
    /**
     * @Inject
     * @var SystemPostMapper
     */
    public $mapper;

}
