<?php

namespace App\Middlewares;

use App\Controllers\PageController;
use League\Pipeline\StageInterface;

class PageMiddleware implements StageInterface
{
    public function __invoke($wp_query)
    {
        $app = $wp_query->get('app');

        $queriedObject = $wp_query->get_queried_object();

        $pageController = $app->make(PageController::class);

        if ($queriedObject instanceof \WP_Post && is_page() && is_page() && is_singular()) {
            $pageController->single();

            die();
        }

        if ((is_front_page() && is_home()) || is_home()) {
            $pageController->posts();

            die();
        }

        if (is_front_page()) {
            $pageController->front();

            die();
        }

        if (is_404()) {
            $pageController->notFound();

            die();
        }

        return $wp_query;
    }
}
