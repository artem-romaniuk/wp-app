<?php

namespace App\Components\MetaBox\Constructor\components;

class VideoAndText
{
    public $name = 'Видео и текст';

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
        $video = [
            'name' => $name . '[' . $key . '][content][video_src]',
            'value' => isset($value['content']['video_src']) ? $value['content']['video_src'] : ''
        ];
        $text = [
            'name' => $name . '[' . $key . '][content][text]',
            'value' => isset($value['content']['text']) ? $value['content']['text'] : ''
        ];
        ?>

        <div class="body-block body-video-text">
            <div class="video-block">
                <label><?php _e('Позиция картинки '); ?></label>
                <select name="<?php echo $position['name']; ?>">
                    <?php foreach ($positions as $key => $name) : ?>
                        <option value="<?php echo $key; ?>"<?php echo ($position['value'] == $key) ? ' selected' : ''; ?>><?php echo $name; ?></option>
                    <?php endforeach; ?>
                </select>

                <label><?php _e('Ссылка на видео'); ?></label>
                <input type="text" name="<?php echo $video['name']; ?>" value="<?php echo $video['value']; ?>">
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

                .body-video-text {
                    flex-wrap: wrap;
                }

                .video-block {
                    margin-bottom: 15px;
                    width: 100%;
                }

                .video-block input {
                    width: 50%;
                }

                .body-video-text .text-block textarea {
                    height: 250px!important;
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