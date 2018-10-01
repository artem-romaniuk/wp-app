<?php

namespace App\Controllers;

use App\Core\Controller\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function single()
    {
        $this->view('post/single');
    }

    public function archive()
    {
        $this->view('post/archive');
    }
}