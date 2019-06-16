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
            return new Theme($container);
        };

        $container['option'] = function ($container) {
            return new OptionCreator($container['config.options']);
        };

        $container['post_type'] = function ($container) {
            return new PostType($container['config.types']);
        };

        $container['taxonomy'] = function ($container) {
            return new Taxonomy($container['config.types']);
        };

        $container['post_meta_box'] = function ($container) {
            return new MetaPostCreator($container['config.types']);
        };

        $container['term_meta_box'] = function ($container) {
            return new MetaTermCreator($container['config.types']);
        };
    }
}
