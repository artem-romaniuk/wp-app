<?php

function getOption($option, $default = '') {

    $locale = explode('_', get_locale());

    return get_option(strtolower($locale[0]) . '_' . $option, $default);
}
