<?php

namespace App\Core\MetaBox;

abstract class BaseMetaBox
{
    protected $name;

    protected $label;

    protected $value;

    protected $params;

    public function __construct($name, $label, $value, array $params = [])
    {
        $this->name = $name;

        $this->label = $label;

        $this->value = $value;

        $this->params = $params;
    }

    abstract function html();

    public static function beforeOutput($value)
    {
        return $value;
    }

    public static function beforeSave($value)
    {
        return $value;
    }
}
