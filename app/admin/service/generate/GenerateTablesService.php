<?php

declare(strict_types=1);

namespace app\admin\service\generate;


use app\admin\mapper\generate\GenerateTablesMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class GenerateTablesService extends AbstractService
{
    /**
     * @Inject
     * @var GenerateTablesMapper
     */
    public $mapper;

}
