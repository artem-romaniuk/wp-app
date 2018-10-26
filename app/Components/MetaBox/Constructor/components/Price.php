<?php

namespace App\Components\MetaBox\Constructor\components;

class Price
{
    public $name = 'Цена';

    protected static $placeholder = '#priceElementId';


    public function html($key, $name, $value)
    {
        $price = [
            'name' => $name . '[' . $key . '][content][price_element]',
            'value' => (isset($value['content']['price_element']) && is_array($value['content']['price_element'])) ? $value['content']['price_element'] : []
        ];
        ?>

        <div class="body-block">
            <div class="price-elements-body">
                <label><?php _e('Элементы цен'); ?></label>
                <div style="display: none">
                    <li data-item-id="<?php echo self::$placeholder; ?>" class="item-price-template">
                        <input type="text" placeholder="<?php _e('Цена'); ?>" class="price-value" name="<?php echo $price['name']; ?>[<?php echo self::$placeholder; ?>][value]" disabled="disabled">
                        <input type="text" placeholder="<?php _e('Валюта'); ?>" class="price-currency" name="<?php echo $price['name']; ?>[<?php echo self::$placeholder; ?>][currency]" disabled="disabled">
                        <input type="text" placeholder="<?php _e('Комментарий'); ?>" class="price-comment" name="<?php echo $price['name']; ?>[<?php echo self::$placeholder; ?>][comment]" disabled="disabled">

                        <button type="button" class="delete-price-element">
                            <?php _e('Delete'); ?>
                        </button>
                    </li>
                </div>

                <ul class="price-elements-container">
                    <?php foreach ($price['value'] as $id => $value) : ?>
                        <li data-item-id="<?php echo $id; ?>">
                            <input type="text" placeholder="<?php _e('Цена'); ?>" class="price-value" name="<?php echo $price['name']; ?>[<?php echo $id; ?>][value]" value="<?php echo $value['value']; ?>">
                            <input type="text" placeholder="<?php _e('Валюта'); ?>" class="price-currency" name="<?php echo $price['name']; ?>[<?php echo $id; ?>][currency]" value="<?php echo $value['currency']; ?>">
                            <input type="text" placeholder="<?php _e('Комментарий'); ?>" class="price-duration" name="<?php echo $price['name']; ?>[<?php echo $id; ?>][comment]" value="<?php echo $value['comment']; ?>">

                            <button type="button" class="delete-price-element">
                                <?php _e('Delete'); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="button button-secondary add-<?php echo $this->name ?>"><?php _e('Add'); ?></button>
            </div>
        </div>

        <?php
    }

    public function handlerStyle()
    {
        /*
        add_action('admin_footer', function () { ?>

            <style type="text/css">



            </style>

        <?php });
        */
    }

    public function handlerScript()
    {
        add_action('admin_footer', function () { ?>

            <script type="text/javascript">
                jQuery(document).ready(function($) {

                    const prefix = '<?php echo $this->name; ?>';
                    const placeholder = '<?php echo self::$placeholder; ?>';

                    $(document).on('click', '.add-' + prefix, function () {
                        const container = $(this).parents('.price-elements-body');
                        const itemTemplate = container.find('.item-price-template');
                        const itemsContainer = container.find('.price-elements-container');

                        createItem(container, itemTemplate, itemsContainer, placeholder);

                        itemsContainer.find('input').each(function () {
                            $(this).prop('disabled', false);
                        });
                    });

                    $(document).on('click', '.delete-price-element', function () {
                        deleteItem($(this));
                    });

                });
            </script>

        <?php });
    }
}