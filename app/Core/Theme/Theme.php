<?php

namespace App\Core\Theme;

use Pimple\Container;

class Theme
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function init()
    {
        $this->setLangDirectory();

        add_action('after_setup_theme', [$this, 'setSupport']);
        add_action('after_setup_theme', [$this, 'registerNavMenu']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyleAndScripts']);
        add_action('widgets_init', [$this, 'sidebars']);

        /* Set styles and scripts in Admin panel */
        add_action('admin_enqueue_scripts', function() {
            wp_enqueue_style('admin-theme-style', $this->getSrcResource('app/assets/css/style.css'), [], false, 'all');
            wp_enqueue_script('admin-theme-script', $this->getSrcResource('app/assets/js/script.js'), ['jquery'], false, true);
        });
        /* End Set styles and scripts in Admin panel */

        $this->setImageSize();

        $this->removing();
    }

    protected function isConfig($name)
    {
        return isset($this->container['config.' . $name]) ? $this->container['config.' . $name] : null;
    }

    protected function getConfig($name)
    {
        return $this->container['config.' . $name];
    }

    public function removing()
    {
        if ($this->isConfig('remove')) {
            foreach ((array) $this->getConfig('remove') as $handler => $data) {
                $function = 'remove_' . $handler;

                if (!function_exists($function)) continue;

                foreach ($data as $hook => $removing) {
                    foreach ($removing as $item) {
                        $function($hook, $item[0], isset($item[1]) ? (int) $item[1] : 10);
                    }
                }
            }
        }
    }

    public function sidebars()
    {
        if ($this->isConfig('sidebars')) {
            foreach ((array) $this->getConfig('sidebars') as $id => $params) {
                $params['id'] = $id;

                register_sidebar($params);
            }
        }
    }

    public function setSupport()
    {
        if ($this->isConfig('support')) {
            foreach ((array) $this->getConfig('support') as $name => $support) {
                is_array($support) ? add_theme_support($name, $support) : add_theme_support($support);
            }
        }
    }

    public function enqueueStyleAndScripts()
    {
        $this->enqueueStyle();
        $this->enqueueScripts();
    }

    protected function enqueueStyle()
    {
        if ($this->isConfig('style')) {
            foreach ((array) $this->getConfig('style')['set'] as $handle => $params) {
                wp_enqueue_style($handle, $this->getSrcResource($params['src']), $params['dependence'], $params['version'], $params['media']);

                if (isset($params['inline'])) {
                    wp_add_inline_style($handle, $params['inline']);
                }
            }

            wp_enqueue_style('default-theme', get_stylesheet_uri());

            foreach ((array) $this->getConfig('style')['unset'] as $handle) {
                wp_deregister_style($handle);
            }
        }
    }

    protected function enqueueScripts()
    {
        if ($this->isConfig('script')) {
            foreach ((array) $this->getConfig('script')['set'] as $handle => $params) {
                wp_enqueue_script($handle, $this->getSrcResource($params['src']), $params['dependence'], $params['version'], $params['in_footer']);

                if (isset($params['localize'])) {
                    wp_localize_script($handle, $params['localize'][0], $params['localize'][1]);
                }
            }

            foreach ((array) $this->getConfig('script')['unset'] as $handle) {
                wp_deregister_script($handle);
            }
        }
    }

    public function setImageSize()
    {
        if ($this->isConfig('image') && function_exists('add_image_size')) {
            foreach ((array) $this->getConfig('image') as $name => $params) {
                add_image_size($name, $params['width'], $params['height'], $params['crop']);
            }
        }
    }

    public function registerNavMenu()
    {
        if ($this->isConfig('menu') && function_exists('register_nav_menus')) {
            register_nav_menus((array) $this->getConfig('menu'));
        }
    }

    public function setLangDirectory()
    {
        if ($this->isConfig('app')) {
            $directory = $this->container['path.base'] . DIRECTORY_SEPARATOR . trim($this->getConfig('app')['languages_path'], '/');

            if (!is_dir($directory)) {
                mkdir($directory, 0775);
            }

            load_theme_textdomain($this->getConfig('app')['text_domain'], $directory);
        }
    }

    protected function isExternalResource($src)
    {
        return (strpos($src, '//') !== false || strpos($src, 'http://') !== false || strpos($src, 'https://') !== false) ? true : false;
    }

    protected function getSrcResource($src)
    {
        return $this->isExternalResource($src) ? $src : get_template_directory_uri() . '/' . trim($src, '/');
    }
}
