<?php

namespace App\Components\MetaBox;

use App\Core\MetaBox\BaseMetaBox;

class Textarea extends BaseMetaBox
{
    public function html()
    {
        echo '<div class="form-group">';
        echo '<label for="' . $this->name . '">' . $this->label . '</label>';
        echo '<textarea id="' . $this->name . '" class="form-control" name="' . $this->name . '">' . $this->value . '</textarea>';
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