<?php

namespace plugin\stone\app\validate\setting;

use plugin\stone\nyuwa\NyuwaValidate;

class TableValidate extends NyuwaValidate
{
    protected $rule = [
        'name' => 'required|regex:/^[A-Za-z0-9_]{2,}$/i',
        'module' => 'required|regex:/^[A-Za-z0-9_]{2,}$/i',
        'pk' => 'required|regex:/^[A-Za-z0-9_]{2,}$/i',
        'engine' => 'required',
        'comment' => 'required',
        'columns' => 'required|array',
        'autoTime' => 'required|boolean',
        'autoUser' => 'required|boolean',
        'softDelete' => 'required|boolean',
        'snowflakeId' => 'required|boolean',
//            'migrate' => 'required|boolean',
    ];

    protected $customAttributes = [
    ];

    protected $scene = [
        "tableCreate" => ["name","module","pk","engine","comment","autoTime","autoUser","softDelete","snowflakeId"]
    ];

}