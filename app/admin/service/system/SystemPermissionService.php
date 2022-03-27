<?php


namespace app\admin\service\system;


use app\admin\mapper\system\SystemPermissionMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemPermissionService extends AbstractService
{


    /**
     * @Inject
     * @var SystemPermissionMapper
     */
    public $mapper;

}
