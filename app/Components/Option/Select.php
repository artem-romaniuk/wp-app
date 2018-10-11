<?php

namespace App\Components\Option;

use App\Core\Option\BaseOption;

class Select extends BaseOption
{
    public function html()
    {
        echo '<div class="form-group">';

        echo '<select name="' . $this->name . '">';
        if (isset($this->params['list']))
        {
            foreach ((array) $this->params['list'] as $key => $value)
            {
                $selected = ($key == $this->value) ? ' selected' : '';
                echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
            }
        }
        echo '</select>';

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