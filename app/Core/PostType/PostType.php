<?php

namespace App\Core\PostType;

class PostType
{
    protected $scope;

    public function __construct(array $scope = [])
    {
        $this->scope = $scope;
    }

    public function create()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        foreach ($this->scope as $name => $components)
        {
            if (isset($components['type']))
            {
                register_post_type($name, $components['type']);
            }
        }
    }
}