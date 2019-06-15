<?php

namespace App\Middlewares;

use League\Pipeline\StageInterface;

class TermMiddleware implements StageInterface
{
    public function __invoke($wp_query)
    {
        $app = $wp_query->get('app');

        $queriedObject = $wp_query->get_queried_object();

        if ($queriedObject instanceof \WP_Term) {

            $class = 'App\Controllers\\' . ucfirst(get_post_type()) . 'Controller';

            if (class_exists($class)) {
                $app->make($class)->taxonomy($queriedObject);

                die();
            }
        }

        return $wp_query;
    }
}
