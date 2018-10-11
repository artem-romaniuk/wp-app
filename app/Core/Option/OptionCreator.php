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
        $locale = '';

        if (isset($_GET['options_lang']))
        {
            $locale = strtolower($_GET['options_lang']);
        }
        elseif (function_exists('icl_get_default_language'))
        {
            $locale = icl_get_default_language();
        }

        return $locale != '' ? $locale . '_' : '';
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
        echo '<div class="custom-options-container">';

        foreach ($this->scope[$page]['sections'] as $section)
        {
            $group = $section['group'];

            $action = ($this->locale != '') ? '?options_lang=' . trim($this->locale, '_') : '';

            echo '<form action="options.php' . $action . '" method="post" class="custom-options-form">';
            settings_fields($group);
            do_settings_sections($group);
            submit_button();
            echo '</form>';
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
        if (function_exists('icl_get_languages') && function_exists('icl_get_default_language'))
        {
            $languages = icl_get_languages();

            echo '<div class="options-language-switcher-container">';
            echo '<label>' . __('Options language') . '</label>';
            echo '<select name="options_lang">';
            foreach ((array) $languages as $key => $detail)
            {
                $selectedLanguage = ($key == trim($this->locale, '_')) ? ' selected' : '';
                echo '<option value="' . $key . '"' . $selectedLanguage . '>' . $detail['native_name'] . '</option>';
            }
            echo '</select>';
            echo '</div>';
        }
    }
}