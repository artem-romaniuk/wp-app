<?php

$app = new App\Core\Application\Application(realpath(__DIR__));


$appDir = dirname(__FILE__);

$f = $appDir . DIRECTORY_SEPARATOR . 'app.php';

$config = [

    'app' => ''

];

$c = $appDir . DIRECTORY_SEPARATOR . 'config/';

$g = 'hello';

echo '<pre>';
print_r((array) $g);
echo '</pre>';