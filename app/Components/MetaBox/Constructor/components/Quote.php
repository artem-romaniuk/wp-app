<?php

namespace App\Components\MetaBox\Constructor\components;

class Quote
{
    public $name = 'Цитата';

    public function html($key, $name, $value)
    {
        $text = [
            'name' => $name . '[' . $key . '][content][text]',
            'value' => isset($value['content']['text']) ? $value['content']['text'] : ''
        ];
        ?>

        <div class="body-block">
            <div class="quote-part">
                <textarea name="<?php echo $text['name']; ?>"><?php echo $text['value']; ?></textarea>
            </div>
        </div>

        <?php
    }

    public function handlerStyle()
    {
        add_action('admin_footer', function () { ?>

            <style type="text/css">

                .quote-part,
                .quote-part textarea {
                    width: 100%;
                    height: 100px;
                }

            </style>

        <?php });
    }

    public function handlerScript()
    {
        /*
        add_action('admin_footer', function () { ?>

            <script type="text/javascript">

            </script>

        <?php });
        */
    }
}