<?php

namespace App\Middlewares;

use League\Pipeline\StageInterface;

class UserMiddleware implements StageInterface
{
    public function __invoke($wp_query)
    {
        $app = $wp_query->get('app');

        $queriedObject = $wp_query->get_queried_object();

        if ($queriedObject instanceof \WP_User) {
            // UserController
        }

        return $wp_query;
    }
}
