<?php

namespace App\Core\MetaBox;


class MetaTermCreator
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
        foreach ($this->scope as $screen => $components) {
            if (isset($components['metas']['term']))  {
                foreach ($components['metas']['term'] as $box => $metas) {
                    $this->metas[$box] = $metas;
                }
            }
        }
    }

    public function create()
    {
        foreach ($this->metas as $id => $meta) {
            foreach ((array) $meta['taxonomy'] as $taxonomy) {
                add_action($taxonomy . '_add_form_fields', [$this, 'outputBoxHtml'], 10, 2);
                add_action($taxonomy . '_edit_form_fields', [$this, 'outputBoxHtml'], 10, 2);

                add_action('edit_term', [$this, 'save'], 10, 1);
                add_action('create_' . $taxonomy, [$this, 'save']);
            }
        }
    }

    public function outputBoxHtml($term)
    {
        echo '<tr class="form-field custom-term-metas"><td colspan="2">';

        foreach ($this->metas as $id => $meta) {
            wp_nonce_field($id, $id . '_wp_nonce', false, true);

            foreach ((array) $meta['fields'] as $field => $params) {
                $label = $params['label'];
                $componentClass = $params['component'];
                $single = $params['single'];
                $params = $params['params'];

                if ($this->isMetaBoxClass($componentClass)) {
                    $name = $this->fullNameField($id, $field);

                    $value = $componentClass::beforeOutput(get_metadata('term', $term->term_id, $name, $single));

                    (new $componentClass($name, $label, $value, $params))->html();
                }
            }
        }

        echo '</td></tr>';
    }

    protected function fullNameField($id, $field)
    {
        return $id . '_' . $field;
    }

    protected function isMetaBoxClass($class)
    {
        return class_exists($class);
    }

    public function save($term_id)
    {
        foreach ($this->metas as $id => $meta) {
            $fields = $meta['fields'];

            if (!$this->canSave($id)) continue;

            foreach ($fields as $field => $params) {
                $typeClass = $params['component'];

                $name = $this->fullNameField($id, $field);

                $value = null;

                if ($this->isMetaBoxClass($typeClass)) {
                    $value = $typeClass::beforeSave($_POST[$name]);
                }

                if ($value) {
                    update_metadata('term', $term_id, $name, $value);
                } else {
                    delete_metadata('term', $term_id, $name);
                }
            }
        }
    }

    protected function canSave($id)
    {
        if (!isset($_POST[$id . '_wp_nonce'])) {
            return false;
        }

        if (!wp_verify_nonce($_POST[$id . '_wp_nonce'], $id)) {
            return false;
        }

        return true;
    }
}