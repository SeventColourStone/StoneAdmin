<?php


namespace plugin\stone\app\validate\setting;


use plugin\stone\nyuwa\NyuwaValidate;

class ModuleValidate extends NyuwaValidate
{
    protected $rule = [
        'name' => 'required|regex:/^[A-Za-z]{2,}$/i',
        'label' => 'required',
        'version' => 'required|regex:/^[0-9\.]{3,}$/',
        'description' => 'required|max:255',

        'status' => 'required',

    ];

    protected $customAttributes = [

    ];

    protected $scene = [
        "moduleCreate" => ["name","label","version","description"],
        "moduleStatus" => ["name","status"],
    ];

}