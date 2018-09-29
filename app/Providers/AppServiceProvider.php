<?php

namespace App\Providers;

use App\Core\Theme\Theme;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['theme'] = function ($container) {
            return new Theme($container);
        };

    }
}