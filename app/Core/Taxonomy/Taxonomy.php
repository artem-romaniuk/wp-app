<?php

namespace App\Core\Taxonomy;

class Taxonomy
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
            $this->factory($name, $components);
        }
    }

    protected function factory($name, array $components = [])
    {
        if (isset($components['taxonomies']))
        {
            foreach ($components['taxonomies'] as $taxonomy => $params)
            {
                register_taxonomy($taxonomy, (array) $name, $params);
            }
        }
    }
}