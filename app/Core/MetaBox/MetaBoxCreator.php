<?php

namespace App\Core\MetaBox;

class MetaBoxCreator
{
    protected $scope;

    protected $metas = [];

    public function __construct(array $scope = [])
    {
        $this->scope = $scope;

        $this->normalize();
    }

    protected function normalize()
    {
        foreach ($this->scope as $screen => $components)
        {
            if (isset($components['metas']))
            {
                foreach ($components['metas'] as $box => &$metas)
                {
                    $metas['screen'] = isset($metas['screen']) ? array_merge((array) $metas['screen'], (array) $screen) : (array) $screen;
                }

                $this->metas = $components['metas'];
            }
        }
    }

    public function create()
    {
        add_action('add_meta_boxes', [$this, 'register'], 10, 2);

        add_action('save_post', [$this, 'save'], 10, 3);
    }

    public function register()
    {
        foreach ($this->metas as $id => $meta)
        {
            $label = $meta['label'];
            $screen = $meta['screen'];
            $position = $meta['position'];
            $priority = $meta['priority'];
            $fields = $meta['fields'];

            add_meta_box($id, $label, [$this, 'outputBoxHtml'], $screen, $position, $priority, ['id' => $id, 'fields' => $fields]);
        }
    }

    public function outputBoxHtml($post, $arguments)
    {
        $id = $arguments['args']['id'];
        $fields = $arguments['args']['fields'];

        wp_nonce_field($id, $id . '_wp_nonce', false, true);

        echo '<div class="meta-boxes-container">';

        foreach ($fields as $field => $params)
        {
            $label = $params['label'];
            $typeClass = $params['component'];
            $single = $params['single'];
            $params = $params['params'];

            if ($this->isMetaBoxClass($typeClass))
            {
                $name = $this->fullNameField($id, $field);

                $value = $typeClass::beforeOutput(get_post_meta($post->ID, $name, $single));

                (new $typeClass($name, $label, $value, $params))->html();
            }
        }

        echo '</div>';
    }

    protected function fullNameField($id, $field)
    {
        return $id . '_' . $field;
    }

    protected function isMetaBoxClass($class)
    {
        return class_exists($class);
    }

    public function save($post_id, $post)
    {
        foreach ($this->metas as $id => $meta)
        {
            $screen = $meta['screen'];
            $fields = $meta['fields'];

            if (!$this->canSave($post, $screen, $id))
            {
                return;
            }

            foreach ($fields as $field => $params)
            {
                $typeClass = $params['component'];

                $name = $this->fullNameField($id, $field);

                $value = null;

                if ($this->isMetaBoxClass($typeClass))
                {
                    $value = $typeClass::beforeSave($_POST[$name]);
                }

                if ($value)
                {
                    update_post_meta($post_id, $name, $value);
                }
                else
                {
                    delete_post_meta($post_id, $name);
                }
            }
        }
    }

    protected function canSave(\WP_Post $post, $name, $id)
    {
        if (!isset($_POST[$id . '_wp_nonce']))
        {
            return false;
        }

        if (!wp_verify_nonce($_POST[$id . '_wp_nonce'], $id))
        {
            return false;
        }

        if (!in_array($post->post_type, (array) $name))
        {
            return false;
        }

        return true;
    }
}