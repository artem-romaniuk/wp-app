<?php

$app = new \App\Core\Application\Application(realpath(__DIR__ . '/../'));

app('theme')->init();

//echo '<pre>';
//print_r(app('theme'));
//echo '</pre>';
