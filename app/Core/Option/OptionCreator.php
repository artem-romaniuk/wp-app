<?php

namespace App\Core\Option;

class OptionCreator
{
    protected $scope;

    protected $locale;

    public function __construct(array $scope = [])
    {
        $this->scope = $scope;

        $this->locale = $this->setLocale();
    }

    protected function setLocale()
    {
        if (isset($_GET['lang']) && $_GET['lang'] != 'all')
        {
            $locale = strtolower($_GET['lang']);
        }
        elseif (function_exists('icl_get_default_language'))
        {
            $locale = icl_get_default_language();
        }
        else
        {
            $locale = '';
        }

        return $locale != '' ? $locale . '_' : '';
    }

    public function create()
    {
        foreach ($this->scope as $page => $options)
        {
            $method = 'on' . implode('', array_map(function ($item) {
                    return ucfirst($item);
                }, explode('_', $page)));

            if (method_exists($this, $method))
            {
                foreach ($options as $params)
                {
                    $this->$method($page, $params);

                    $this->outputFields($page);
                }
            }
        }
    }

    public function onMenuPage($page, array $params)
    {
        add_action('admin_menu', function() use($page, $params) {

            $page_title = $params['page_title'];
            $menu_label = $params['menu_label'];
            $capability = $params['capability'];
            $page_slug = $params['page_slug'];
            $icon_url = $params['icon_url'];
            $position = $params['position'];

            add_menu_page($page_title, $menu_label, $capability, $page_slug, function() use($page) {
                $this->outputSection($page);
            }, $icon_url, $position);
        });
    }

    public function onSubmenuPage($page, array $params)
    {
        add_action('admin_menu', function() use($page, $params) {

            $parent_slug = $params['parent_slug'];
            $page_title = $params['page_title'];
            $menu_label = $params['menu_label'];
            $capability = $params['capability'];
            $page_slug = $params['page_slug'];

            add_submenu_page($parent_slug, $page_title, $menu_label, $capability, $page_slug, function() use($page) {
                $this->outputSection($page);
            });
        });
    }

    public function onThemePage($page, array $params)
    {
        add_action('admin_menu', function() use($page, $params) {

            $page_title = $params['page_title'];
            $menu_label = $params['menu_label'];
            $capability = $params['capability'];
            $page_slug = $params['page_slug'];

            add_theme_page($page_title, $menu_label, $capability, $page_slug, function() use($page) {
                $this->outputSection($page);
            });
        });
    }

    public function outputSection($page)
    {
        $current_screen = get_current_screen();

        echo '<div class="custom-options-container">';

        foreach ($this->scope[$page] as $options) {

            if ((isset($options['parent_slug']) && $current_screen->parent_file != $options['parent_slug']) || (isset($options['page_slug']) && $current_screen->parent_file != $options['page_slug'])) continue;

            foreach ($options['sections'] as $section) {
                $group = $section['group'];

                $action = ($this->locale != '') ? '?lang=' . trim($this->locale, '_') : '';

                echo '<form action="options.php' . $action . '" method="post" class="custom-options-form">';
                settings_fields($group);
                do_settings_sections($group);
                submit_button();
                echo '</form>';
            }

        }

        echo '</div>';
    }

    public function outputFields($page)
    {
        add_action('admin_init', function () use($page) {
            $this->settingsField($page);
        });
    }

    public function settingsField($page)
    {
        foreach ($this->scope[$page] as $options)
        {
            $sections = $options['sections'];

            foreach ($sections as $id => $section) {
                $label = $section['label'];
                $group = $section['group'];
                $fields = $section['fields'];

                add_settings_section($id, $label, function () use ($id, $section) {
                    $description = $section['description'];
                    echo '<span id="options_section_' . $id . '" class="option-section-description">' . $description . '</span>';
                }, $group);

                foreach ($fields as $field => $params) {
                    $componentClass = $params['component'];

                    if ($this->isComponentClass($componentClass)) {
                        $name = $this->fullNameField($id, $field);
                        $label = $params['label'];

                        register_setting($group, $name, [
                            'type' => 'string',
                            'group' => $group,
                            'description' => '',
                            'sanitize_callback' => [$this, 'sanitize'],
                            'show_in_rest' => false,
                        ]);

                        add_settings_field($name, $label, function () use ($componentClass, $name, $params) {
                            $value = $componentClass::beforeOutput(get_option($name));
                            $params = $params['params'];
                            (new $componentClass($name, $value, $params))->html();
                        }, $group, $id);
                    }
                }
            }
        }
    }

    public function sanitize($value)
    {
        if (is_string($value))
            return htmlspecialchars($value);

        return $value;
    }

    protected function fullNameField($id, $field)
    {
        return $this->locale . $id . '_' . $field;
    }

    protected function isComponentClass($class)
    {
        return class_exists($class);
    }
}