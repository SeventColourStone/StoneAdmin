<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemPostMapper;
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
