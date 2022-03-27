<?php


namespace nyuwa\bootstrap;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use nyuwa\event\TestEvent;
use nyuwa\listener\TestListener;
use support\Log;
use Webman\Bootstrap;
use support\Db;

class LaravelLog implements Bootstrap
{
    public static function start($worker)
    {
        Db::listen(function ($query) {
            $sql = $query->sql;
            $bindings = [];
            if ($query->bindings) {
                foreach ($query->bindings as $v) {
                    if (is_numeric($v)) {
                        $bindings[] = $v;
                    } else {
                        $bindings[] = '"' . strval($v) . '"';
                    }
                }
            }
            $execute = Str::replaceArray('?', $bindings, $sql);
            Log::info('log test:'.$execute);
        });

    }
}