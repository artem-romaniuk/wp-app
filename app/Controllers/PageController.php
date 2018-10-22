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
        $this->view('page/single', [

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