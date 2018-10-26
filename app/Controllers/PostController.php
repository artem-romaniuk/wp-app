<?php

namespace App\Controllers;

use App\Core\Controller\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function single()
    {
        global $wp_query;

        $this->view('post/single', [
            'page' => $wp_query->post,
            'article' => $wp_query->post
        ]);
    }

    public function taxonomy()
    {
        global $wp_query;

        $this->view('post/taxonomy', [
            'page' => get_post(get_option('page_for_posts')),
            'articles' => $wp_query->posts
        ]);
    }
}