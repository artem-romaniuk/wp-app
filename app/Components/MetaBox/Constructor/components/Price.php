<?php

namespace App\Components\MetaBox\Constructor\components;

class Price
{
    public $name = 'Цена';


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
                <div class="scope-price-elements" style="display: none">
                    <li data-id-element="#priceElementId">
                        <input type="text" placeholder="<?php _e('Цена'); ?>" class="price-value" name="<?php echo $price['name']; ?>[#priceElementId][value]" disabled="disabled">
                        <input type="text" placeholder="<?php _e('Валюта'); ?>" class="price-currency" name="<?php echo $price['name']; ?>[#priceElementId][currency]" disabled="disabled">
                        <input type="text" placeholder="<?php _e('Комментарий'); ?>" class="price-comment" name="<?php echo $price['name']; ?>[#priceElementId][comment]" disabled="disabled">
                        <button type="button" class="delete-price-element"><?php _e('Delete'); ?></button>
                    </li>
                </div>

                <ul class="price-elements-container">
                    <?php foreach ($price['value'] as $id => $value) : ?>
                        <li data-id-element="<?php echo $id; ?>">
                            <input type="text" placeholder="<?php _e('Цена'); ?>" class="price-value" name="<?php echo $price['name']; ?>[<?php echo $id; ?>][value]" value="<?php echo $value['value']; ?>">
                            <input type="text" placeholder="<?php _e('Валюта'); ?>" class="price-currency" name="<?php echo $price['name']; ?>[<?php echo $id; ?>][currency]" value="<?php echo $value['currency']; ?>">
                            <input type="text" placeholder="<?php _e('Комментарий'); ?>" class="price-duration" name="<?php echo $price['name']; ?>[<?php echo $id; ?>][comment]" value="<?php echo $value['comment']; ?>">
                            <button type="button" class="delete-price-element"><?php _e('Delete'); ?></button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="button button-secondary add-price-element"><?php _e('Add'); ?></button>
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

                $(function () {

                    const priceElementsContainer = $('.price-elements-container');
                    const scopePriceElement = $('.scope-price-elements').find('li');

                    $(document).on('click', '.add-price-element', function (e) {

                        e.preventDefault();

                        const priceElementsContainer = $(this)
                            .parents('.component-container')
                            .find('.price-elements-container');

                        const priceElement = $(this)
                            .parents('.component-container')
                            .find('.scope-price-elements')
                            .find('li');

                        const cloneListElement = priceElement
                            .clone()[0]
                            .outerHTML
                            .replaceAll('#priceElementId', computationPriceElementId(this));

                        priceElementsContainer.append(cloneListElement);

                        priceElementsContainer.find('input').each(function () {
                            $(this).prop('disabled', false);
                        });
                    });

                    $(document).on('click', '.delete-price-element', function (e) {
                        e.preventDefault();
                        $(this)
                            .parent()
                            .remove();
                    });

                    function computationPriceElementId(object) {

                        const listElements = $(object)
                            .parents('.component-container')
                            .find('.price-elements-container')
                            .children();

                        if (listElements.length > 0) {

                            const arrayElements = [];

                            for (let i = 0; i < listElements.length; i++) {

                                let propertyValue = +$(listElements[i]).attr('data-id-element');
                                arrayElements.push(propertyValue);
                            }

                            return Math.max.apply(null, arrayElements) + 1;
                        }

                        return 1;
                    }
                });

            </script>

        <?php });
    }
}