<?php


namespace plugin\stone\nyuwa;


use Hyperf\Di\Annotation\Inject;
use plugin\stone\nyuwa\traits\ControllerTrait;
use Psr\Container\ContainerInterface;
use support\Request;

/**
 * 后台控制器基类
 * Class NyuwaController
 * @package nyuwa
 */
abstract class NyuwaController
{
    use ControllerTrait;


    //对于授权auth通过后的接口Permission,切片不兼容的情况下，先使用数据库存储对应的接口跟Permission code的绑定关系。
    //使用中间件进行权限验证。

    //跟

}
