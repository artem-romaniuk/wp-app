<?php

defined('ABSPATH') or die('Access denied');


global $app, $wp_query;

$queriedObject = $wp_query->get_queried_object();

$defaultController = 'App\Controllers\DefaultController';


// Это главная страница по умодчанию (выводятся посты)
if (is_front_page() && is_home()) {
    $app->make('App\Controllers\PageController')->posts();
}
// Это главная страница установленная в настройках
elseif (is_front_page()) {
    $app->make('App\Controllers\PageController')->front();
}
// Это страница постов
elseif (is_home()) {
    $app->make('App\Controllers\PageController')->posts();
}
elseif (is_404()) {
    $app->make('App\Controllers\PageController')->notFound();
}
elseif ($queriedObject instanceof \WP_Post) {

    // Это отдельная запись
    if ('post' === $queriedObject->post_type && is_single()) {
        $app->make('App\Controllers\PostController')->single();
    }
    // Это стандартная страница
    elseif (is_page() && is_page() && is_singular()) {
        $app->make('App\Controllers\PageController')->single();
    }
    // Это стандартная страница произвольного типа записей
    else {
        $type = ucfirst($queriedObject->post_type);
        $class = 'App\Controllers\\' . $type . 'Controller';
        $make = class_exists($class) ? $class : $defaultController;
        $app->make($make)->single();
    }

}
elseif ($queriedObject instanceof \WP_Term) {

    $type = ucfirst(get_post_type());
    $class = 'App\Controllers\\' . $type . 'Controller';
    $make = class_exists($class) ? $class : $defaultController;

    $app->make($make)->taxonomy($queriedObject);
}
elseif ($queriedObject instanceof \WP_User) {

    $app->make($defaultController)->user();
}
elseif (is_year() || is_month() || is_day()) {

    $class = 'App\Controllers\PostController';
    $make = class_exists($class) ? $class : $defaultController;

    $app->make($make)->taxonomy(null);
}
