<?php

namespace App\Core\Container;

use Pimple\Container as PimpleContainer;

class Container extends PimpleContainer
{
    public static $instance;


    public static function setInstance(array $values = [])
    {
        return static::$instance = new static($values);
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}