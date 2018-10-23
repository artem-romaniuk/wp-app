<?php

namespace App\Components\MetaBox;

use App\Core\MetaBox\BaseMetaBox;

class Editor extends BaseMetaBox
{
    public function html()
    {
        echo '<div class="form-group">';
        echo '<label for="' . $this->name . '">' . $this->label . '</label>';

        wp_editor($this->value, $this->name, [
            'wpautop' => 1,
            'media_buttons' => 1,
            'textarea_name' => $this->name,
            'textarea_rows' => 20,
            'tabindex' => null,
            'editor_css' => '',
            'editor_class' => '',
            'teeny' => 0,
            'dfw' => 0,
            'tinymce' => 1,
            'quicktags' => 1,
            'drag_drop_upload' => false
        ]);

        echo '</div>';
    }

    public static function beforeOutput($value)
    {
        return $value;
    }

    public static function beforeSave($value)
    {
        return $value;
    }
}