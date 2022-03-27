<?php

declare(strict_types=1);

namespace app\admin\service\test;


use app\admin\mapper\test\TestInfo1Mapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class TestInfo1Service extends AbstractService
{
    /**
     * @Inject
     * @var TestInfo1Mapper
     */
    public $mapper;

}
