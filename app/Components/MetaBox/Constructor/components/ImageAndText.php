<?php

namespace App\Components\MetaBox\Constructor\components;

class ImageAndText
{
    public $name = 'Картинка и текст';

    protected static $defaultImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQMAAABKLAcXAAAABlBMVEUAAAC7u7s37rVJAAAAAXRSTlMAQObYZgAAACJJREFUOMtjGAV0BvL/G0YMr/4/CDwY0rzBFJ704o0CWgMAvyaRh+c6m54AAAAASUVORK5CYII=';


    public function html($key, $name, $value)
    {
        $positions = [
            'top' => __('Вверху'),
            'right' => __('Справа'),
            'bottom' => __('Внизу'),
            'left' => __('Слква')
        ];
        $position = [
            'name' => $name . '[' . $key . '][content][image_position]',
            'value' => isset($value['content']['image_position']) ? $value['content']['image_position'] : 'left'
        ];
        $text = [
            'name' => $name . '[' . $key . '][content][text]',
            'value' => isset($value['content']['text']) ? $value['content']['text'] : ''
        ];
        $image_id = [
            'name' => $name . '[' . $key . '][content][image][id]',
            'value' => isset($value['content']['image']['id']) ? $value['content']['image']['id'] : '0'
        ];
        ?>

        <div class="body-block">
            <div class="image-block">
                <label><?php _e('Позиция картинки '); ?></label>
                <select name="<?php echo $position['name']; ?>">
                    <?php foreach ($positions as $key => $name) : ?>
                        <option value="<?php echo $key; ?>"<?php echo ($position['value'] == $key) ? ' selected' : ''; ?>><?php echo $name; ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="image-wrapper">
                    <a class="attach-image" href="#">
                        <img src="<?php echo $image_id['value'] != 0 ? wp_get_attachment_image_url($image_id['value'], 'thumbnail') : self::$defaultImage; ?>" alt="">
                    </a>

                    <button type="button" class="button button-secondary delete-image"><?php _e('Remove'); ?></button>

                    <input type="hidden" name="<?php echo $image_id['name']; ?>" value="<?php echo $image_id['value']; ?>" class="image-id">
                </div>
            </div>

            <div class="text-block">
                <textarea name="<?php echo $text['name']; ?>"><?php echo $text['value']; ?></textarea>
            </div>
        </div>

        <?php
    }

    public function handlerStyle()
    {
        add_action('admin_footer', function () { ?>

            <style type="text/css">

                .image-block {
                    width: 200px;
                    padding: 0 15px;
                }

                .image-block img {
                    width: 200px;
                    height: auto;
                    margin: 5px 0;
                }

                .text-block {
                    -webkit-align-self: stretch;
                    align-self: stretch;
                    width: 100%;
                }

                .text-block textarea {
                    width: 100%;
                    height: 100%!important;
                }

                .delete-image {
                    width: 200px;
                    margin: 0 auto;
                }

            </style>

        <?php });
    }

    public function handlerScript()
    {
        add_action('admin_footer', function () { ?>

            <script type="text/javascript">

                $(function () {
                    $(document).on('click', '.attach-image', function (e) {
                        choiceImage(e, this);
                    });
                    $(document).on('click', '.delete-image', function () {
                        removeImage(this);
                    });
                });

            </script>

        <?php });
    }
}