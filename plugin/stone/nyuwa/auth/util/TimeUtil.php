<?php


namespace plugin\stone\nyuwa\auth\util;
use Carbon\Carbon;

class TimeUtil
{
    /**
     * Get the Carbon instance for the current time.
     *
     * @return \Carbon\Carbon
     */
    public static function now()
    {
        return Carbon::now('UTC');
    }

    /**
     * Get the Carbon instance for the timestamp.
     *
     * @param  int  $timestamp
     *
     * @return \Carbon\Carbon
     */
    public static function timestamp($timestamp)
    {
        return Carbon::createFromTimestampUTC($timestamp)->timezone('UTC');
    }

    /**
     * Checks if a timestamp is in the past.
     *
     * @param  int  $timestamp
     * @param  int  $leeway
     *
     * @return bool
     */
    public static function isPast($timestamp, $leeway = 0)
    {
        return static::timestamp($timestamp)->addSeconds($leeway)->isPast();
    }

    /**
     * Checks if a timestamp is in the future.
     *
     * @param  int  $timestamp
     * @param  int  $leeway
     *
     * @return bool
     */
    public static function isFuture($timestamp, $leeway = 0)
    {
        return static::timestamp($timestamp)->subSeconds($leeway)->isFuture();
    }
}
