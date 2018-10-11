<?php

namespace App\Components\Option;

use App\Core\Option\BaseOption;

class DynamicList extends BaseOption
{
    public function html()
    { ?>

        <div class="form-group">
            <div class="items-container list-items">
                <ul style="display: none;">
                    <li data-item-id="#listItemId" class="item-template">
                        <input type="text" name="<?php echo $this->name; ?>[#listItemId]">
                        <button type="button" class="button delete-item"><?php _e('Delete'); ?></button>
                    </li>
                </ul>

                <ul class="items-container">
                    <?php foreach ($this->value as $key => $value) : ?>
                        <li data-item-id="<?php echo $key; ?>">
                            <input type="text" name="<?php echo $this->name; ?>[<?php echo $key; ?>]" value="<?php echo $value; ?>">
                            <button type="button" class="button delete-item"><?php _e('Delete'); ?></button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="button button-add add-<?php echo $this->name ?>"><?php _e('Add'); ?></button>
            </div>
        </div>

    <?php add_action('admin_footer', function () { ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {

                const prefix = '<?php echo $this->name ?>';

                $(document).on('click', '.add-' + prefix, function () {
                    const container = $(this).parents('.items-container');
                    const itemTemplate = container.find('.item-template');
                    const itemsContainer = container.find('.items-container');

                    createItem(container, itemTemplate, itemsContainer, '#listItemId');
                });

                $(document).on('click', '.delete-item', function () {
                    deleteItem($(this));
                });

            });
        </script>

    <?php }, 99);
    }

    public static function beforeOutput($value)
    {
        if (is_array($value) && isset($value['#listItemId']))
        {
            unset($value['#listItemId']);
        }

        return (array) $value;
    }

    public static function beforeSave($value)
    {
        return $value;
    }
}