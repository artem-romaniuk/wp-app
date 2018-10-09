<?php

defined('ABSPATH') or die('Access denied');

//if (defined('WP_DEBUG') && WP_DEBUG === false) {
    show_admin_bar(false);
//}

require_once get_template_directory() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

app('theme');
app('option');
app('post_type');
app('taxonomy');
app('meta_box');






