<?php

function dump($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

function trans($text = '', $domain = false) {
    $domain = ($domain) ? (string) $domain : (defined('TEXT_DOMAIN') ? TEXT_DOMAIN : '');

    return translate($text, $domain);
}


function getCustomOption($option, $default = '') {
    $locale = explode('_', get_locale());
    $result = get_option(strtolower($locale[0]) . '_' . $option);

    return $result ? $result : (getOption($option) ? getOption($option) : $default);
}

function getOption($option, $default = '') {

    return get_option($option, $default);
}
