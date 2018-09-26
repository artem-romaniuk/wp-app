<?php

global $wp_query;

$queriedObject = $wp_query->get_queried_object();

if ($queriedObject instanceof \WP_Post)
{
    $type = $queriedObject->post_type;

    var_dump($type);
}

if ($queriedObject instanceof \WP_Term)
{
    $taxonomy = $queriedObject->taxonomy;

    var_dump($taxonomy);
}

if ($queriedObject instanceof \WP_User)
{

}
