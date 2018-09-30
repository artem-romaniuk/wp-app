<?php

namespace App\Providers;

use App\Models\Page;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ModelServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['model.page'] = function ($container) {
            return new Page($container);
        };
    }
}