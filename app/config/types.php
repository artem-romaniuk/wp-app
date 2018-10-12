<?php

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
                        'name' => 'test',
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