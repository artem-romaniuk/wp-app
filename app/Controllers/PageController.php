<?php

namespace App\Controllers;

use App\Core\Controller\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function front()
    {
        echo 'Front Page';
    }

    public function single()
    {
        echo 'Static Page';
    }

    public function posts()
    {
        echo 'Page with posts';
    }

    public function archive()
    {

    }
}