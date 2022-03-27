<?php

declare (strict_types = 1);

namespace nyuwa\auth\facade;

/**
 * Class JWT
 * @package nyuwa\auth\facade
 * @method \nyuwa\auth\JWT make(array $extend,int $access_exp = 0,int $refresh_exp = 0) static 生成令牌
 */
class JWT
{
    protected static $_instance = null;


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new \nyuwa\auth\JWT();
        }
        return static::$_instance;
    }
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::instance()->{$name}(... $arguments);
    }
}