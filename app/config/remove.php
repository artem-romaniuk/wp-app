<?php

return [

    'action' => [

        'wp_head' => [
            ['feed_links_extra', 3],
            ['feed_links', 2],
            ['rsd_link'],
            ['wlwmanifest_link'],
            ['wp_generator'],
            ['start_post_rel_link'],
            ['index_rel_link'],
            ['adjacent_posts_rel_link_wp_head'],
            ['wp_shortlink_wp_head'],
            ['rest_output_link_wp_head'],
            ['wp_oembed_add_discovery_links'],
            ['print_emoji_detection_script', 7],
            ['rest_output_link_wp_head',],
            ['wp_oembed_add_discovery_links'],
            ['wp_oembed_add_host_js']
        ],

        'template_redirect' => [
            ['rest_output_link_header', 11],
        ],

        'wp_print_styles' => [
            ['print_emoji_styles'],
        ],

        'xmlrpc_rsd_apis' => [
            ['rest_output_rsd'],
        ],

        'auth_cookie_malformed' => [
            ['rest_cookie_collect_status'],
        ],

        'auth_cookie_expired' => [
            ['rest_cookie_collect_status'],
        ],

        'auth_cookie_bad_username' => [
            ['rest_cookie_collect_status'],
        ],

        'auth_cookie_bad_hash' => [
            ['rest_cookie_collect_status'],
        ],

        'auth_cookie_valid' => [
            ['rest_cookie_collect_status'],
        ],

        'init' => [
            ['rest_api_init'],
        ],

        'rest_api_init' => [
            ['rest_api_default_filters'],
            ['wp_oembed_register_route'],
        ],

        'parse_request' => [
            ['rest_api_loaded'],
        ],

    ],

    'filter' => [

        'rest_authentication_errors' => [
            ['rest_cookie_check_errors', 100],
        ],

        'rest_pre_serve_request' => [
            ['_oembed_rest_pre_serve_request'],
        ],

        'pre_term_description' => [
            ['wp_filter_kses'],
            ['wp_kses_data'],
        ],

    ],

];
