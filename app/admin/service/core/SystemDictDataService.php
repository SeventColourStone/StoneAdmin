<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemDictDataMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemDictDataService extends AbstractService
{
    /**
     * @Inject
     * @var SystemDictDataMapper
     */
    public $mapper;

}
