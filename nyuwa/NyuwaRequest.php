<?php


namespace nyuwa;


use support\Request;

class NyuwaRequest extends Request
{
    public function get($name = null, $default = null)
    {
        return $this->filter(parent::get($name, $default));
    }

    public function post($name = null, $default = null)
    {
        return $this->filter(parent::post($name, $default));
    }

    public function filter($value)
    {
        if (!$value) {
            return $value;
        }
        if (is_array($value)) {
            array_walk_recursive($value, function(&$item){
                if (is_string($item)) {
                    $item = htmlspecialchars($item);
                }
            });
        } else {
            $value = htmlspecialchars($value);
        }
        return $value;
    }
}
