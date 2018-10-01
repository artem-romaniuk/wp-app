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

        $all_pages = 'All pages';

        $this->view('page/single', [
            'all' => $all_pages
        ]);
    }

    public function posts()
    {
        $this->view('page/posts');
    }

    public function notFound()
    {
        $this->view('page/404');
    }
}