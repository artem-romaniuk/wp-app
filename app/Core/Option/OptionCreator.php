<?php

namespace App\Core\Option;

class OptionCreator
{
    protected $scope;

    protected $options = [];


    public function __construct(array $scope = [])
    {
        $this->scope = $scope;

        $this->normalize();
    }

    protected function normalize()
    {
        foreach ($this->scope as $page => $components)
        {
            if (isset($components['metas']))
            {
                foreach ($components['metas'] as $box => &$metas)
                {
                    $metas['screen'] = isset($metas['screen']) ? array_merge((array) $metas['screen'], (array) $screen) : (array) $screen;
                }

                $this->metas = $components['metas'];
            }
        }
    }

    public function create()
    {
        //echo 'Option created';
    }


    protected $sections = [];
    protected $arguments = [
        'section_id' => '',
        'section_title' => '',
        'section_page' => '',
        'section_description' => ''
    ];
    protected $options = [];
    public function section(array $arguments = [])
    {
        array_push($this->sections, array_merge($this->arguments, $arguments));
        return $this;
    }
    public function outputSection()
    {
        foreach ($this->sections as $section) :
            echo '<form action="options.php" method="post">';
            settings_fields($section['section_page']);
            do_settings_sections($section['section_page']);
            submit_button();
            echo '</form>';
        endforeach;
    }
    public function outputFields()
    {
        add_action('admin_init', [$this, 'addSettingsField']);
    }
//    public function create($name, $label = '', $field_type = '', array $params = [])
//    {
//        $this->options[$this->getEndKeySection()][$name] = [
//            'label' => $label,
//            'field_type' => $field_type,
//            'params' => $params
//        ];
//        return $this;
//    }
    public function addSettingsField()
    {
        foreach ($this->sections as $key => $arguments) :
            add_settings_section($arguments['section_id'], $arguments['section_title'], function () use($arguments) {
                echo '<span id="options_section_' . $arguments['section_id'] . '" class="option-section-description">' . $arguments['section_description'] . '</span>';
            }, $arguments['section_page']);
            if (isset($this->options[$key])) :
                foreach ($this->options[$key] as $name => $params) :
                    $typeClass = $this->optionClassName($params['field_type']);
                    if ($this->isOptionClass($typeClass)) {
                        $name = $this->fullNameField($key, $name);
                        register_setting($arguments['section_page'], $name);
                        add_settings_field($name, $params['label'], function () use ($typeClass, $name, $params) {
                            $value = $typeClass::beforeOutput(get_option($name));
                            (new $typeClass($name, $params['field_type'], $value, $params['params']))->html();
                        }, $arguments['section_page'], $arguments['section_id']);
                    }
                endforeach;
            endif;
        endforeach;
    }
    protected function fullNameField($key, $name)
    {
        return $this->sections[$key]['section_id'] . '_' . $name;
    }
    protected function optionClassName($type)
    {
        return __NAMESPACE__ . '\\Option' . ucwords($type);
    }
    protected function isOptionClass($class)
    {
        return class_exists($class);
    }
    /*
     * $position :
     * 2 Консоль
     * 4 Разделитель
     * 5 Посты
     * 10 Медиа
     * 15 Ссылки
     * 20 Страницы
     * 25 Комментарии
     * 59 Разделитель
     * 60 Внешний вид
     * 65 Плагины
     * 70 Пользователи
     * 75 Инструменты
     * 80 Настройки
     * 99 Разделитель
     */
    public function onMenuPage($page_title, $menu_title, $page_slug, $icon_url = '', $position = 999, $capability = 'manage_options')
    {
        add_action('admin_menu', function() use($page_title, $menu_title, $page_slug, $icon_url, $position, $capability) {
            add_menu_page($page_title, $menu_title, $capability, $page_slug, [$this, 'outputSection'], $icon_url, $position);
        });
        $this->outputFields();
    }
    /*
     * $parent_slug :
     * index.php - Консоль (Dashboard). Или спец. функция: add_dashboard_page();
     * edit.php - Посты (Posts). Или спец. функция: add_posts_page();
     * upload.php - Медиафайлы (Media). Или спец. функция: add_media_page();
     * link-manager.php - Ссылки (Links). Или спец. функция: add_links_page();
     * edit.php?post_type=page - Страницы (Pages). Или спец. функция: add_pages_page();
     * edit-comments.php - Комментарии (Comments). Или спец. функция: add_comments_page();
     * edit.php?post_type=your_post_type - Произвольные типы записей.
     * themes.php - Внешний вид (Appearance). Или спец. функция: add_theme_page();
     * plugins.php - Плагины (Plugins). Или спец. функция: add_plugins_page();
     * users.php - Пользователи (Users). Или спец. функция: add_users_page();
     * tools.php - Инструменты (Tools). Или спец. функция: add_management_page();
     * options-general.php - Настройки (Settings). Или спец. функция: add_options_page()
     * settings.php - Настройки (Settings) сети сайтов в MU режиме.
     */
    public function onSubmenuPage($parent_slug, $page_title, $menu_title, $page_slug, $capability = 'manage_options')
    {
        add_action('admin_menu', function() use($parent_slug, $page_title, $menu_title, $page_slug, $capability) {
            add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $page_slug, [$this, 'outputSection']);
        });
        $this->outputFields();
    }
    public function onThemePage($page_title, $menu_title, $page_slug, $capability = 'manage_options')
    {
        add_action('admin_menu', function() use($page_title, $menu_title, $page_slug, $capability) {
            add_theme_page($page_title, $menu_title, $capability, $page_slug, [$this, 'outputSection']);
        });
        $this->outputFields();
    }
    protected function getEndKeySection()
    {
        end($this->sections);
        return key($this->sections);
    }
}