<?php

namespace App\Core\PostType;

class PostType
{
    protected $types = [
        'post_type' => [],
        'taxonomy' => []
    ];

    protected $labels = [
        'post_type' => [
            'name' => '',
            'singular_name' => '',
            'add_new' => '',
            'add_new_item' => '',
            'edit_item' => '',
            'new_item' => '',
            'view_item' => '',
            'search_items' => '',
            'not_found' =>  '',
            'not_found_in_trash' => '',
            'parent_item_colon' => '',
            'menu_name' => ''
        ],
        'taxonomy' => [
            'name' => '',
            'singular_name' => '',
            'search_items' => '',
            'all_items' => '',
            'view_item' => '',
            'parent_item' => '',
            'parent_item_colon' => '',
            'edit_item' => '',
            'update_item' => '',
            'add_new_item' => '',
            'new_item_name' => '',
            'menu_name' => '',
        ]
    ];

    protected $params = [
        'post_type' => [
            'description' => '',
            'public' => true,
            'publicly_queryable' => null,
            'exclude_from_search' => null,
            'show_ui' => null,
            'show_in_menu' => null,
            'show_in_admin_bar' => null,
            'show_in_nav_menus' => null,
            'show_in_rest' => null,
            'rest_base' => null,
            'menu_position' => null,
            'menu_icon' => null,
            //'capability_type' => 'post',
            //'capabilities' => 'post',
            //'map_meta_cap' => null,
            'hierarchical' => false,
            'supports' => ['title', 'editor'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
            'taxonomies' => [],
            'has_archive' => false,
            'rewrite' => true,
            'query_var' => true,
        ],
        'taxonomy' => [
            'description' => '',
            'public' => true,
            'publicly_queryable' => null,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_tagcloud' => true,
            'show_in_rest' => null,
            'rest_base' => null,
            'hierarchical' => true,
            'update_count_callback' => '',
            'rewrite' => true,
            'query_var' => true,
            'capabilities' => [],
            'meta_box_cb' => null,
            'show_admin_column' => false,
            '_builtin' => false,
            'show_in_quick_edit' => null,
        ]
    ];


    public function type($type, array $labels = [], array $params = [])
    {
        $this->types['post_type'][$type] = [
            'labels' => array_merge($this->labels['post_type'], $labels),
            'params' => array_merge($this->params['post_type'], $params)
        ];

        return $this;
    }

    public function withTaxonomy($taxonomy, array $labels = [], array $params = [])
    {
        $key = $this->getEndKeyPostType();

        $this->types['post_type'][$key]['params']['taxonomies'][] = $taxonomy;

        $this->types['taxonomy'][$taxonomy] = [
            'object_type' => $key,
            'labels' => array_merge($this->labels['taxonomy'], $labels),
            'params' => array_merge($this->params['taxonomy'], $params)
        ];

        return $this;
    }

    public function get()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        foreach ($this->types['taxonomy'] as $taxonomy => $params) {
            register_taxonomy($taxonomy, [$params['object_type']], array_merge(['label' => null, 'labels' => $params['labels']], $params['params']));
        }

        foreach ($this->types['post_type'] as $type => $params) {
            register_post_type($type, array_merge(['label' => null, 'labels' => $params['labels']], $params['params']));
        }
    }

    protected function getEndKeyPostType()
    {
        end($this->types['post_type']);

        return key($this->types['post_type']);
    }
}