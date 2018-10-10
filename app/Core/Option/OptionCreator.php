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
        if (isset($_GET['options_lang']))
        {
            return strtolower($_GET['options_lang']) . '_';
        }

        return '';
    }

    public function create()
    {
        foreach ($this->scope as $page => $params)
        {
            $method = 'on' . implode('', array_map(function ($item) {
                return ucfirst($item);
                }, explode('_', $page)));

            if (method_exists($this, $method))
            {
                $this->$method($page, $params);

                $this->outputFields($page);
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
                $this->selectLanguage();
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
                $this->selectLanguage();
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
                $this->selectLanguage();
                $this->outputSection($page);
            });
        });
    }

    public function outputSection($page)
    {
        foreach ($this->scope[$page]['sections'] as $section)
        {
            $group = $section['group'];

            echo '<form action="options.php" method="post">';
            settings_fields($group);
            do_settings_sections($group);
            submit_button();
            echo '</form>';
        }
    }

    public function outputFields($page)
    {
        add_action('admin_init', function () use($page) {
            $this->settingsField($page);
        });
    }

    public function settingsField($page)
    {
        $sections = $this->scope[$page]['sections'];

        foreach ($sections as $id => $section)
        {
            $label = $section['label'];
            $group = $section['group'];
            $fields = $section['fields'];

            add_settings_section($id, $label, function () use($id, $section) {
                $description = $section['description'];
                echo '<span id="options_section_' . $id . '" class="option-section-description">' . $description . '</span>';
            }, $group);

            foreach ($fields as $field => $params)
            {
                $componentClass = $params['component'];

                if ($this->isComponentClass($componentClass))
                {
                    $name = $this->fullNameField($id, $field);
                    $label = $params['label'];

                    register_setting($group, $name);

                    add_settings_field($name, $label, function () use ($componentClass, $name, $params) {
                        $value = $componentClass::beforeOutput(get_option($name));
                        $params = $params['params'];
                        (new $componentClass($name, $value, $params))->html();
                    }, $group, $id);
                }
            }
        }
    }

    protected function fullNameField($id, $field)
    {
        return $this->locale . $id . '_' . $field;
    }

    protected function isComponentClass($class)
    {
        return class_exists($class);
    }

    protected function selectLanguage()
    {
        if (function_exists('icl_get_languages'))
        {
            echo '<pre>';
            print_r(icl_get_languages());
            echo '</pre>';
        }
    }
}