<?php

namespace App\Components\MetaBox;

use App\Core\MetaBox\BaseMetaBox;

class Date extends BaseMetaBox
{
    public function html()
    {
        echo '<div class="form-group">';
        echo '<label for="' . $this->name . '">' . $this->label . '</label>';
        echo '<input id="' . $this->name . '" class="form-control" type="date" name="' . $this->name . '" value="' . $this->value . '">';
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