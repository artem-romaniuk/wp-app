<?php

namespace App\Core\Model;

use App\Core\Container\Container;

class Model
{
    protected $container;

    protected $postsPerPage;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->postsPerPage = (int) get_option('posts_per_page', 10);
    }
}
