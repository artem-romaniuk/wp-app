<?php

namespace App\Core\MetaBox;

class MetaBoxCreator
{
    protected $scope;

    public function __construct(array $scope = [])
    {
        $this->scope = $scope;
    }

    public function register()
    {
        foreach ($this->scope as $name => $components)
        {
            $this->factory($name, $components);
        }
    }

    public function factory($name, $components)
    {
        if (isset($components['metas']))
        {
            foreach ($components['metas'] as $id => $meta)
            {
                add_meta_box($id, $meta['label'], [$this, 'outputBoxHtml'], $name, $meta['position'], $meta['priority'], ['id' => $id, 'params' => $meta]);
            }
        }
    }

    public function create()
    {
        add_action('add_meta_boxes', [$this, 'register'], 10, 2);

        //add_action('save_post', [$this, 'save'], 10, 3);
    }

    public function outputBoxHtml($post, $arguments)
    {
        $id = $arguments['args']['id'];
        $fields = $arguments['args']['params']['fields'];

        wp_nonce_field($id, $id . '_wp_nonce', false, true);

        echo '<div class="meta-boxes-container">';

        foreach ($fields as $name => $params)
        {
            $label = $params['label'];
            $typeClass = $params['component'];
            $single = $params['single'];
            $params = $params['params'];

            if ($this->isMetaBoxClass($typeClass))
            {
                $name = $this->fullNameField($id, $name);

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

    public function save($id, $post)
    {
        if (!$this->canSave($post))
        {
            return;
        }

        foreach ($this->types as $name => $params)
        {
            $name = $this->fullNameField($name);

            $value = null;

            $typeClass = $this->metaBoxClassName($params['type']);

            if ($this->isMetaBoxClass($typeClass)) {
                $value = $typeClass::beforeSave($_POST[$name]);
            }

            if ($value) {
                update_post_meta($id, $name, $value);
            }
            else {
                delete_post_meta($id, $name);
            }
        }
    }

    protected function canSave(\WP_Post $post)
    {
        if (!isset($_POST[$this->arguments['id'] . '_wp_nonce'])) {
            return false;
        }

        if (!wp_verify_nonce($_POST[$this->arguments['id'] . '_wp_nonce'], $this->arguments['id'])) {
            return false;
        }

        if (!in_array($post->post_type, $this->arguments['screen'])) {
            return false;
        }

        return true;
    }
}