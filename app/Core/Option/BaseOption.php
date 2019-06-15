<?php

namespace App\Core\Option;

abstract class BaseOption
{
    protected $name;

    protected $value;

    protected $params;


    public function __construct($name, $value, array $params = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->params = $params;
    }

    public function html() {}

    public static function beforeOutput($value)
    {
        return $value;
    }

    public static function beforeSave($value)
    {
        return $value;
    }
}
