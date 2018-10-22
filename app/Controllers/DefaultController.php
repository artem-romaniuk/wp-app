<?php

namespace App\Controllers;

use App\Core\Controller\Controller;

class DefaultController extends Controller
{
    protected $layout = 'default';


    public function single()
    {
        $this->view('default/single');
    }

    public function taxonomy($taxonomy)
    {
        $this->view('default/archive');
    }
}