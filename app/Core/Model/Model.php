<?php

namespace App\Core\Model;


use App\Core\Container\Container;

class Model
{
    protected $postType = 'post';

    protected $container;

    protected $postsPerPage;


    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->postsPerPage = (int) get_option('posts_per_page', 10);
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