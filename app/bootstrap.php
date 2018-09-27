<?php

$app = new App\Core\Application\Application(realpath(__DIR__));


$appDir = dirname(__FILE__);

$f = $appDir . DIRECTORY_SEPARATOR . 'app.php';

$config = [

    'app' => ''

];

$c = $appDir . DIRECTORY_SEPARATOR . 'config/';


echo '<pre>';
print_r(realpath(__DIR__));
echo '</pre>';