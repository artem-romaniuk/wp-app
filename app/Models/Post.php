<?php

namespace App\Models;

use App\Core\Model\Model;

class Post extends Model
{
    protected $postType = 'post';

    //protected $postsPerPage;


    public function all()
    {
        return $this->queryPost([
            'posts_per_page' => $this->postsPerPage,
            'post_type' => $this->postType,
        ]);
    }

    public function find($id)
    {
        return $this->queryPost([
            'p' => (int) $id,
            'post_type' => $this->postType,
        ]);
    }
}