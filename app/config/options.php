<?php

return [

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
    'menu_page' => [

        'page_title' => 'Custom options',
        'menu_label' => 'Custom options',
        'capability' => 'manage_options',
        'position' => 999,
        'icon_url' => '',
        'page_slug' => 'page_options',

        'sections' => [

            'id_section_1' =>  [

                'label' => 'Section options 1',
                'group' => 'options_1',
                'description' => '',
                'fields' => [

                    'name_field_1' => [
                        'label' => 'Options 1',
                        'component' => 'App\Components\Option\Text',
                        'params' => [

                        ]
                    ],
                    'name_field_2' => [
                        'label' => 'Options 2',
                        'component' => 'App\Components\Option\Textarea',
                        'params' => [

                        ]
                    ]

                ]
            ],

            'id_section_2' =>  [

                'label' => 'Section options 2',
                'group' => 'options_2',
                'description' => '',
                'fields' => [

                    'name_field_1' => [
                        'label' => 'Options 1',
                        'component' => 'App\Components\Option\Number',
                        'params' => [

                        ],
                    ],
                    'name_field_2' => [
                        'label' => 'Options 2',
                        'component' => 'App\Components\Option\Email',
                        'params' => [

                        ],
                    ],

                ],
            ],
        ],
    ],


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

    /*
    'submenu_page' => [
        'parent_slug' => 'plugins.php',
        'page_title' => 'Custom options',
        'menu_label' => 'Custom options',
        'capability' => 'manage_options',
        'page_slug' => 'sub_page_options',

        'sections' => [

            // Sections

        ],

    ],
    */

    /*
    'theme_page' => [
        'page_title' => 'Custom options',
        'menu_label' => 'Custom options',
        'capability' => 'manage_options',
        'page_slug' => 'theme_option',

        'sections' => [

            // Sections

        ],
    ],
    */

];