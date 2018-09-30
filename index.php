<?php

defined('ABSPATH') or die('Access denied');

wp_head();

global $app, $wp_query;

$queriedObject = $wp_query->get_queried_object();

if (is_home() && is_front_page()) {
    $app->make('App\Controllers\PageController')->posts();
    //echo 'Это главная страница по умодчанию (выводятся посты)';
}
elseif (is_front_page()) {
    $app->make('App\Controllers\PageController')->front();
    //echo 'Это главная страница установленная в настройках';
}
elseif (is_home()) {
    $app->make('App\Controllers\PageController')->posts();
    //echo 'Это страница постов';
}
elseif ($queriedObject instanceof \WP_Post) {

    if ('post' === $queriedObject->post_type && is_single()) {
        $app->make('App\Controllers\PostController')->single();
        //echo 'Это отдельная запись';
    }
    elseif (is_page() && is_page() && is_singular()) {
        $app->make('App\Controllers\PageController')->single();
        //echo 'Это стандартная страница';
    }
    else {
        $type = ucfirst($queriedObject->post_type);
        $class = 'App\Controllers\\' . $queriedObject->post_type . 'Controller';
        $make = class_exists($class) ? $class : 'App\Controllers\DefaultController';
        $app->make($make)->single();

        //echo 'Это стандартная страница ' . $queriedObject->post_type;
    }

}
elseif ($queriedObject instanceof \WP_Term) {

    $postType = get_post_type();
    $taxonomy = $queriedObject->taxonomy;

    $object = $app->make('App\Controllers\\' . $queriedObject->post_type . 'Controller');

    var_dump($postType);
    var_dump($taxonomy);

}
elseif ($queriedObject instanceof \WP_User) {

    echo '<pre>';
    print_r($queriedObject);
    echo '</pre>';

}























wp_footer();
