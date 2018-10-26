<?php

namespace App\Controllers;

use App\Core\Controller\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function front()
    {
        $this->view('page/front');
    }

    public function single()
    {
        global $wp_query;

        $this->view('page/single', [
            'page' => $wp_query->post
        ]);
    }

    public function posts()
    {
        global $wp_query;

        $this->view('page/posts', [
            'page' => get_post(get_option('page_for_posts')),
            'articles' => $wp_query->posts
        ]);
    }

    public function notFound()
    {
        $this->view('page/404');
    }
}