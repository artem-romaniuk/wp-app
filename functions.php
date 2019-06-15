<?php

use App\Middlewares\PageMiddleware;
use App\Middlewares\PostsMiddleware;
use League\Pipeline\Pipeline;
use App\Middlewares\CustomPostTypeMiddleware;
use App\Middlewares\TermMiddleware;
use App\Middlewares\UserMiddleware;

defined('ABSPATH') or die('Access denied');

show_admin_bar(false);

require_once get_template_directory() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

add_action('template_redirect', function() {
    app('theme');
    app('option');
    app('post_type');
    app('taxonomy');
    app('meta_box');
    app('widget');

    global $app, $wp_query;

    set_query_var('app', $app);

    $pipeline = (new Pipeline)
        ->pipe(new PostsMiddleware())
        ->pipe(new PageMiddleware())
        ->pipe(new CustomPostTypeMiddleware())
        ->pipe(new TermMiddleware())
        ->pipe(new UserMiddleware())
        ->pipe(function() {
            throw new Exception('Controller not fount');
        });

    try {
        $pipeline->process($wp_query);
    } catch(LogicException $e) {
        echo $e->getMessage();
    }

    die();
});
