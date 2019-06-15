<?php

namespace App\Providers;

use App\Core\MetaBox\MetaPostCreator;
use App\Core\MetaBox\MetaTermCreator;
use App\Core\Option\OptionCreator;
use App\Core\PostType\PostType;
use App\Core\Taxonomy\Taxonomy;
use App\Core\Theme\Theme;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['theme'] = function ($container) {
            $object = new Theme($container);
            $object->init();
        };

        $container['option'] = function ($container) {
            $object = new OptionCreator($container['config.options']);
            $object->create();
        };

        $container['post_type'] = function ($container) {
            $object = new PostType($container['config.types']);
            $object->create();
        };

        $container['taxonomy'] = function ($container) {
            $object = new Taxonomy($container['config.types']);
            $object->create();
        };

        $container['meta_box'] = function ($container) {
            $object = new MetaPostCreator($container['config.types']);
            $object->create();

            $object = new MetaTermCreator($container['config.types']);
            $object->create();
        };
    }
}
