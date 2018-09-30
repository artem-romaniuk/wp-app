<?php

namespace App\Controllers;

use App\Core\Container\Container;
use App\Models\Post;

class PostController extends Container
{
    public function single()
    {
        echo 'Single Post';
    }

    public function archive()
    {
        echo 'Archive Page';
    }
}