<?php

namespace App\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class WidgetServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['widget'] = function ($container) {
            require_once $container['path.app'] . DIRECTORY_SEPARATOR . 'Widgets' . DIRECTORY_SEPARATOR . 'WelcomeWidget.php';

            add_action('widgets_init', function() {

                register_widget('WelcomeWidget');

                unregister_widget('WP_Widget_Archives');
                unregister_widget('WP_Widget_Calendar');
                unregister_widget('WP_Widget_Categories');
                unregister_widget('WP_Widget_Meta');
                unregister_widget('WP_Widget_Pages');
                unregister_widget('WP_Widget_Recent_Comments');
                unregister_widget('WP_Widget_Recent_Posts');
                unregister_widget('WP_Widget_RSS');
                unregister_widget('WP_Widget_Search');
                unregister_widget('WP_Widget_Tag_Cloud');
                unregister_widget('WP_Widget_Text');
                unregister_widget('WP_Nav_Menu_Widget');
                unregister_widget('WP_Widget_Custom_HTML');
                unregister_widget('WP_Widget_Media_Audio');
                unregister_widget('WP_Widget_Media_Video');
                unregister_widget('WP_Widget_Media_Gallery');
                unregister_widget('WP_Widget_Media_Image');
            });
        };
    }
}
