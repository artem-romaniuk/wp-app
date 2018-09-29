<?php

use App\Core\Container\Container;


if (!function_exists('app')) {

    function app($abstract = null)
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        $container = Container::getInstance();

        return $container[$abstract];
    }
}


if (!function_exists('config')) {

    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return Container::getInstance();
        }

        $config = Container::getInstance()->raw('config.' . $key);

        return $config ? $config : $default;
    }
}