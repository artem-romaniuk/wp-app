<?php

namespace App\Core\Taxonomy;

class Taxonomy
{
    protected $scope;

    protected $taxonomies = [];


    public function __construct(array $scope = [])
    {
        $this->scope = $scope;

        $this->normalize();
    }

    protected function normalize()
    {
        foreach ($this->scope as $screen => $components)
        {
            if (isset($components['taxonomies']))
            {
                $components['taxonomies']['screen'] = $screen;

                $this->taxonomies = $components['taxonomies'];
            }
        }
    }

    public function create()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        $screen = $this->taxonomies['screen'];

        foreach ($this->taxonomies as $taxonomy => $params)
        {
            if ($taxonomy == 'screen') continue;

            register_taxonomy($taxonomy, (array) $screen, $params);
        }
    }
}