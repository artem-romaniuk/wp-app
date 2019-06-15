<?php

namespace App\Middlewares;

use League\Pipeline\StageInterface;

class CustomPostTypeMiddleware implements StageInterface
{
    public function __invoke($wp_query)
    {
        $app = $wp_query->get('app');

        $queriedObject = $wp_query->get_queried_object();

        if ($queriedObject instanceof \WP_Post) {
            $class = 'App\Controllers\\' . ucfirst($queriedObject->post_type) . 'Controller';

            if (class_exists($class)) {
                $app->make($class)->single();

                die();
            }
        }

        return $wp_query;
    }
}
