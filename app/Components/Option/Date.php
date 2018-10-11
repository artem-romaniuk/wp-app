<?php

namespace App\Components\Option;

use App\Core\Option\BaseOption;

class Date extends BaseOption
{
    public function html()
    {
        echo '<div class="form-group">';
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