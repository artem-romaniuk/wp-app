<?php

namespace App\Components\MetaBox\Constructor\components;

class DynamicList
{
    public $name = 'Список';

    public function html($key, $name, $value)
    {
        $types = [
            'numeric' => __('Нумерованый'),
            'no_numeric' => __('Не нумерованый'),
        ];
        $type = [
            'name' => $name . '[' . $key . '][content][type]',
            'value' => isset($value['content']['type']) ? $value['content']['type'] : 'no_numeric'
        ];
        $list = [
            'name' => $name . '[' . $key . '][content][list]',
            'value' => (isset($value['content']['list']) && is_array($value['content']['list'])) ? $value['content']['list'] : []
        ];
        ?>

        <div class="body-block">
            <div class="list-elements-body">
                <label>
                    <?php _e('Тип списка'); ?>
                    <select name="<?php echo $type['name']; ?>">
                        <?php foreach ($types as $key => $name) : ?>
                            <option value="<?php echo $key; ?>"<?php echo ($type['value'] == $key) ? ' selected' : ''; ?>><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <div class="scope-list-element" style="display: none">
                    <li data-id-element="#listElementId">
                        <input type="text" name="<?php echo $list['name']; ?>[#listElementId]" value="" disabled="disabled">
                        <button type="button" class="delete-list-element"><?php _e('Delete'); ?></button>
                    </li>
                </div>

                <ul class="list-elements-container">
                    <?php foreach ($list['value'] as $id => $value) : ?>
                        <li data-id-element="<?php echo $id; ?>">
                            <input type="text" name="<?php echo $list['name']; ?>[<?php echo $id; ?>]" value="<?php echo $value; ?>">
                            <button type="button" class="delete-list-element"><?php _e('Delete'); ?></button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="button button-secondary add-list-element"><?php _e('Add'); ?></button>
            </div>
        </div>

        <?php
    }

    public function handlerStyle()
    {
        add_action('admin_footer', function () { ?>

            <style type="text/css">

                .list-elements-body {
                    width: 80%;
                    margin: 0 auto;
                }

                .list-elements-body input {
                    width: calc(100% - 100px);
                }

            </style>

        <?php });
    }

    public function handlerScript()
    {
        add_action('admin_footer', function () { ?>

            <script type="text/javascript">

                $(function () {

                    const listElementsContainer = $('.list-elements-container');
                    const scopeListElement = $('.scope-list-element').find('li');

                    $(document).on('click', '.add-list-element', function (e) {

                        e.preventDefault();

                        const listElementsContainer = $(this)
                            .parents('.component-container')
                            .find('.list-elements-container');

                        const listElement = $(this)
                            .parents('.component-container')
                            .find('.scope-list-element')
                            .find('li');

                        const cloneListElement = listElement
                            .clone()[0]
                            .outerHTML
                            .replaceAll('#listElementId', computationListElementId(this));

                        listElementsContainer.append(cloneListElement);

                        listElementsContainer.find('input').each(function () {
                            $(this).prop('disabled', false);
                        });
                    });

                    $(document).on('click', '.delete-list-element', function (e) {
                        e.preventDefault();
                        $(this)
                            .parent()
                            .remove();
                    });

                    function computationListElementId(object) {

                        const listElements = $(object)
                            .parents('.component-container')
                            .find('.list-elements-container')
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