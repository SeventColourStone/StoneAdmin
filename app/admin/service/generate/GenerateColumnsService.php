<?php

declare(strict_types=1);

namespace app\admin\service\generate;


use app\admin\mapper\generate\GenerateColumnsMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class GenerateColumnsService extends AbstractService
{
    /**
     * @Inject
     * @var GenerateColumnsMapper
     */
    public $mapper;

}
