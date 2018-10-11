<?php

function getCustomOption($option, $default = '') {
    $locale = explode('_', get_locale());
    return get_option(strtolower($locale[0]) . '_' . $option, $default);
}

function getOption($option, $default = '') {
    return get_option($option, $default);
}
