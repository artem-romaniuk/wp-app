<?php

defined('ABSPATH') or die('Access denied');

//if (defined('WP_DEBUG') && WP_DEBUG === false) {
    show_admin_bar(false);
//}

require_once get_template_directory() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

app('theme')->init();



/*
$reflection = new \ReflectionClass('App\Core\Model\Model');
$parameters = $reflection->getMethod('__construct')->getParameters();
foreach ($parameters as $param) {
    var_dump($param->getClass());
}
*/



add_action('init', 'my_custom_init');
function my_custom_init(){
    register_post_type('book', array(
        'labels'             => array(
            'name'               => 'Книги', // Основное название типа записи
            'singular_name'      => 'Книга', // отдельное название записи типа Book
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить новую книгу',
            'edit_item'          => 'Редактировать книгу',
            'new_item'           => 'Новая книга',
            'view_item'          => 'Посмотреть книгу',
            'search_items'       => 'Найти книгу',
            'not_found'          =>  'Книг не найдено',
            'not_found_in_trash' => 'В корзине книг не найдено',
            'parent_item_colon'  => '',
            'menu_name'          => 'Книги'

        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title','editor','author','thumbnail','excerpt','comments')
    ) );
}




add_action( 'init', 'register_faq_post_type' );
function register_faq_post_type() {
    // Раздел вопроса - faqcat
    register_taxonomy('faqcat', array('faq'), array(
        'label'                 => 'Раздел вопроса', // определяется параметром $labels->name
        'labels'                => array(
            'name'              => 'Разделы вопросов',
            'singular_name'     => 'Раздел вопроса',
            'search_items'      => 'Искать Раздел вопроса',
            'all_items'         => 'Все Разделы вопросов',
            'parent_item'       => 'Родит. раздел вопроса',
            'parent_item_colon' => 'Родит. раздел вопроса:',
            'edit_item'         => 'Ред. Раздел вопроса',
            'update_item'       => 'Обновить Раздел вопроса',
            'add_new_item'      => 'Добавить Раздел вопроса',
            'new_item_name'     => 'Новый Раздел вопроса',
            'menu_name'         => 'Раздел вопроса',
        ),
        'description'           => 'Рубрики для раздела вопросов', // описание таксономии
        'public'                => true,
        'show_in_nav_menus'     => false, // равен аргументу public
        'show_ui'               => true, // равен аргументу public
        'show_tagcloud'         => false, // равен аргументу show_ui
        'hierarchical'          => true,
        'rewrite'               => array('slug'=>'faq', 'hierarchical'=>false, 'with_front'=>false, 'feed'=>false ),
        'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
    ) );

    // тип записи - вопросы - faq
    register_post_type('faq', array(
        'label'               => 'Вопросы',
        'labels'              => array(
            'name'          => 'Вопросы',
            'singular_name' => 'Вопрос',
            'menu_name'     => 'Архив вопросов',
            'all_items'     => 'Все вопросы',
            'add_new'       => 'Добавить вопрос',
            'add_new_item'  => 'Добавить новый вопрос',
            'edit'          => 'Редактировать',
            'edit_item'     => 'Редактировать вопрос',
            'new_item'      => 'Новый вопрос',
        ),
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_rest'        => false,
        'rest_base'           => '',
        'show_in_menu'        => true,
        'exclude_from_search' => false,
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'hierarchical'        => false,
        'rewrite'             => array( 'slug'=>'faq/%faqcat%', 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false ),
        'has_archive'         => 'faq',
        'query_var'           => true,
        'supports'            => array( 'title', 'editor' ),
        'taxonomies'          => array( 'faqcat' ),
    ) );

}

## Отфильтруем ЧПУ произвольного типа
// фильтр: apply_filters( 'post_type_link', $post_link, $post, $leavename, $sample );
add_filter('post_type_link', 'faq_permalink', 1, 2);
function faq_permalink( $permalink, $post ){
    // выходим если это не наш тип записи: без холдера %products%
    if( strpos($permalink, '%faqcat%') === false )
        return $permalink;

    // Получаем элементы таксы
    $terms = get_the_terms($post, 'faqcat');
    // если есть элемент заменим холдер
    if( ! is_wp_error($terms) && !empty($terms) && is_object($terms[0]) )
        $term_slug = array_pop($terms)->slug;
    // элемента нет, а должен быть...
    else
        $term_slug = 'no-faqcat';

    return str_replace('%faqcat%', $term_slug, $permalink );
}

