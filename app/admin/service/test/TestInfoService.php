<?php

declare(strict_types=1);

namespace app\admin\service\test;


use app\admin\mapper\test\TestInfoMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class TestInfoService extends AbstractService
{
    /**
     * @Inject
     * @var TestInfoMapper
     */
    public $mapper;

}
