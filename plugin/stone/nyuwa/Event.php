<?php

namespace plugin\stone\nyuwa;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;


/**
 *  class Event
 * @package support
 *
 *  Strings methods
 * @method static \Illuminate\Events\Dispatcher dispatch($event)
 */
class Event
{
    /**
     * @var Dispatcher
     */
    protected static $instance=null;

    /**
     * @return Dispatcher|null
     */
    public static function instance()
    {
        if (!static::$instance) {
            $container        = new Container;
            static::$instance = new Dispatcher($container);
            $eventsList       = config('events');
            if (isset($eventsList['listener']) && !empty($eventsList['listener'])) {
                foreach ($eventsList['listener'] as $event => $listener) {
                    if (is_string($listener)) {
                        $listener = implode(',', $listener);
                    }
                    foreach ($listener as $l) {
                        static::$instance->listen($event, $l);
                    }
                }
            }
            if (isset($eventsList['subscribe']) && !empty($eventsList['subscribe'])) {
                foreach ($eventsList['subscribe'] as  $subscribe) {
                    static::$instance->subscribe($subscribe);
                }
            }
        }
        return static::$instance;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::instance()->{$name}(... $arguments);
    }

}