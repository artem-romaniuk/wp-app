<?php

namespace App\Core\Model;


use App\Core\Container\Container;

class Model
{
    protected $postType = 'post';

    protected $container;


    public function __construct(Container $container, $fd)
    {
        $this->container = $container;
    }

}