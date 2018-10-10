<?php

namespace App\Components\Option;

use App\Core\Option\BaseOption;

class Textarea extends BaseOption
{
    public function html()
    {
        echo '<div class="form-group">';
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