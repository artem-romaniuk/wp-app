<?php

namespace App\Components\MetaBox;

use App\Core\MetaBox\BaseMetaBox;

class Gallery extends BaseMetaBox
{
    protected static $placeholder = '#imageItemId';

    protected static $defaultImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQMAAABKLAcXAAAABlBMVEUAAAC7u7s37rVJAAAAAXRSTlMAQObYZgAAACJJREFUOMtjGAV0BvL/G0YMr/4/CDwY0rzBFJ704o0CWgMAvyaRh+c6m54AAAAASUVORK5CYII=';


    public function html()
    {
        ?>
        <div class="form-group form-group-meta-box gallery-meta-box">
            <label><?php echo $this->label; ?></label>
            <div class="items-container list-items">
                <ul style="display: none;">
                    <li data-item-id="<?php echo self::$placeholder; ?>" class="item-template">
                        <div class="image-wrapper">
                            <a class="attach-image-<?php echo $this->name; ?>" href="#">
                                <img src="<?php echo self::$defaultImage; ?>" alt="">
                            </a>
                            <input type="hidden" name="<?php echo $this->name; ?>[<?php echo self::$placeholder; ?>]" class="image-id">

                            <button type="button" class="button button-secondary remove-image remove-image-<?php echo $this->name; ?>">
                                <?php _e('Remove'); ?>
                            </button>
                        </div>
                    </li>
                </ul>

                <ul class="items-container">
                    <?php foreach ($this->value as $id => $value) : ?>
                        <li data-item-id="<?php echo $id; ?>">
                            <div class="image-wrapper">
                                <a class="attach-image-<?php echo $this->name; ?>" href="#">
                                    <img src="<?php echo $value != 0 ? wp_get_attachment_image_url($value, 'thumbnail') : self::$defaultImage; ?>" alt="">
                                </a>
                                <input type="hidden" name="<?php echo $this->name; ?>[<?php echo $id; ?>]" value="<?php echo $value; ?>" class="image-id">

                                <button type="button" class="button button-secondary remove-image remove-image-<?php echo $this->name; ?>">
                                    <?php _e('Remove'); ?>
                                </button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="button button-add add-<?php echo $this->name; ?>"><?php _e('Add'); ?></button>
            </div>
        </div>


        <?php add_action('admin_footer', function () { ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {

                const prefix = '<?php echo $this->name; ?>';
                const placeholder = '<?php echo self::$placeholder; ?>';

                $(document).on('click', '.add-' + prefix, function () {
                    const container = $(this).parents('.items-container');
                    const itemTemplate = container.find('.item-template');
                    const itemsContainer = container.find('.items-container');

                    createItem(container, itemTemplate, itemsContainer, placeholder);
                });

                $(document).on('click', '.remove-image-' + prefix, function () {
                    removeImage($(this));
                    deleteItem($(this));
                });

                $(document).on('click', '.attach-image-' + prefix, function (e) {
                    choiceImage(e, $(this));
                });

            });
        </script>

    <?php }, 99);
    }

    public static function beforeOutput($value)
    {
        return empty($value) ? [] : (array) $value;
    }

    public static function beforeSave($value)
    {
        if (isset($value[self::$placeholder])) unset($value[self::$placeholder]);

        return $value;
    }
}