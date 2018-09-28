<?php
defined('ABSPATH') or die();

if (defined('WP_DEBUG') && WP_DEBUG === false) {
    show_admin_bar(false);
}



define('TEXT_DOMAIN', 'default');

define('TEMPLATE_DIR', get_template_directory());

define('TEMPLATE_URL', get_template_directory_uri());


require_once TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
