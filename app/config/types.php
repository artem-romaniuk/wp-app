<?php

/*
menu_position (число)
Позиция где должно расположится меню нового типа записи:

1       — в самом верху меню
2-3     — под «Консоль»
4-9     — под «Записи»
10-14   — под «Медиафайлы»
15-19   — под «Ссылки»
20-24   — под «Страницы»
25-59   — под «Комментарии» (по умолчанию, null)
60-64   — под «Внешний вид»
65-69   — под «Плагины»
70-74   — под «Пользователи»
75-79   — под «Инструменты»
80-99   — под «Параметры»
100+    — под разделителем после «Параметры»


supports(массив)
Вспомогательные поля на странице создания/редактирования этого типа записи. Метки для вызова функции add_post_type_support().

title           - блок заголовка;
editor          - блок для ввода контента;
author          - блог выбора автора;
thumbnail       - блок выбора миниатюры записи. Нужно также включить поддержку в установке темы post-thumbnails;
excerpt         - блок ввода цитаты;
trackbacks      - включает поддержку трекбеков и пингов (за блоки не отвечает);
custom-fields   - блок установки произвольных полей;
comments        - блок комментариев (обсуждение);
revisions       - блок ревизий (не отображается пока нет ревизий);
page-attributes - блок атрибутов постоянных страниц (шаблон и древовидная связь записей, древовидность должна быть включена).
post-formats    – блок форматов записи, если они включены в теме.
*/

return [

    'type_name_1' => [

        'type' => [
            'labels' => [
                'name' => 'Книги',
                'singular_name' => 'Книга',
                'add_new' => 'Добавить новую',
                'add_new_item' => 'Добавить новую книгу',
                'edit_item' => 'Редактировать книгу',
                'new_item' => 'Новая книга',
                'view_item' => 'Посмотреть книгу',
                'search_items' => 'Найти книгу',
                'not_found' => 'Книг не найдено',
                'not_found_in_trash' => 'В корзине книг не найдено',
                'parent_item_colon' => '',
                'menu_name' => 'Книги'
            ],
            'public'  => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' =>'faq/%faqcat%',
                'with_front' => false,
                'pages' => false,
                'feeds' => false,
                'feed' => false
            ],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => [
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments'
            ]
        ],

        'taxonomies' => [
            'taxonomy_name_1' => [
                'label' => 'Раздел вопроса',
                'labels' => [
                    'name' => 'Разделы вопросов',
                    'singular_name' => 'Раздел вопроса',
                    'search_items' => 'Искать Раздел вопроса',
                    'all_items' => 'Все Разделы вопросов',
                    'parent_item' => 'Родит. раздел вопроса',
                    'parent_item_colon' => 'Родит. раздел вопроса:',
                    'edit_item' => 'Ред. Раздел вопроса',
                    'update_item' => 'Обновить Раздел вопроса',
                    'add_new_item' => 'Добавить Раздел вопроса',
                    'new_item_name' => 'Новый Раздел вопроса',
                    'menu_name' => 'Раздел вопроса',
                ],
                'description' => 'Рубрики для раздела вопросов',
                'public' => true,
                'show_in_nav_menus' => false,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => [
                    'slug' => 'faq',
                    'hierarchical' => false,
                    'with_front' => false,
                    'feed' => false
                ],
                'show_admin_column' => true,
            ],

            'taxonomy_name_2' => [
                // ...
            ]
        ],

        'metas' => [

            'test_group' => [
                'label' => 'Boxes group Title',
                'position' => 'normal',
                'priority' => 'high',
                'fields' => [

                    'test' => [
                        'label' => 'Title field',
                        'component' => 'App\Components\MetaBox\DynamicList',
                        'single' => true,
                        'params' => [

                        ]
                    ],
                    'test2' => [
                        'label' => 'Title field2',
                        'component' => 'App\Components\MetaBox\Constructor\Constructor',
                        'single' => true,
                        'params' => [

                        ]
                    ],

                ],
            ],

        ]

    ],

    /*
    'type_name_2' => [

        'type' => [

        ],

        'taxonomy' => [

        ],

        'meta' => [

        ]

    ]
    */


];