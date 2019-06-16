<?php

use App\Middlewares\PageMiddleware;
use App\Middlewares\PostsMiddleware;
use League\Pipeline\Pipeline;
use App\Middlewares\CustomPostTypeMiddleware;
use App\Middlewares\TermMiddleware;
use App\Middlewares\UserMiddleware;
use App\Core\Container\Container;

defined('ABSPATH') or die('Access denied');

show_admin_bar(false);

require_once get_template_directory() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$container = Container::getInstance();

$container['theme']->init();
$container['option']->create();
$container['post_type']->create();
$container['taxonomy']->create();
$container['post_meta_box']->create();
$container['term_meta_box']->create();
$container['widget'];

add_action('template_redirect', function() {
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
