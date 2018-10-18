<?php

namespace App\Core\Model;


use App\Core\Container\Container;

class Model
{
    protected $postType = 'post';

    protected $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function queryPost(array $arguments = [])
    {
        return new \WP_Query($arguments);
    }

    protected function queryTerm(array $arguments = [])
    {
        return new \WP_Term_Query($arguments);
    }
}