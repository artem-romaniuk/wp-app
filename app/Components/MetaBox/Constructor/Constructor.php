<?php

namespace App\Components\MetaBox\Constructor;

use App\Core\MetaBox\BaseMetaBox;

class Constructor extends BaseMetaBox
{
    protected static $placeholder = '#listItemId';

    public function html()
    {
        /* Scope section */
        echo '<div id="componentsScopes">';
        echo '<input type="hidden" name="component_placeholder" value="' . self::$placeholder . '">';

        foreach ((array) $this->getFilesComponent() as $name)
        {
            $object = $this->makeObject($name);

            if ($object)
            {
                echo '<button type="button" class="clone-component" data="' . $this->objectWithoutNamespace($object) . '">' . $object->name . '</button>';
                echo '<div style="display: none;">';
                $object->handlerStyle();
                $object->handlerScript();
                $this->componentBody($object, self::$placeholder);
                echo '</div>';
            }
        }
        echo '</div>';
        /* End Scope section */

        /* Output components section */
        echo '<div id="componentsContainer" class="components-container">';

        foreach ((array) $this->value as $key => $component)
        {
            if (isset($component['component']))
            {
                $object = $this->makeObject($component['component']);

                if ($object)
                {
                    $this->componentBody($object, $key, $component);
                }
            }
        }

        echo '</div>';
        /* End Output components section */
    }

    private function componentHeader($object, $key, $value)
    {
        $display = [
            'name' => $this->name . '[' . $key . '][display]',
            'value' => (!isset($value['display'])) ? ' checked' : ($value['display'] == 'on') ? ' checked' : ''
        ];
        $title = [
            'name' => $this->name . '[' . $key . '][content][title]',
            'value' => (isset($value['content']['title'])) ? $value['content']['title'] : ''
        ];
        ?>

        <div class="display-layout"></div>

        <!-- Confirm delete popup -->
        <div class="confirm-delete-component" style="display: none;">
            <h2><?php printf(__('Are you sure you want to delete the component %s?'), $object->name); ?></h2>
            <button class="confirm-action-button" type="button" data-confirm="confirm"><?php _e('Yes'); ?></button>
            <button class="confirm-action-button" type="button" data-confirm="cancel"><?php _e('Cancel'); ?></button>
        </div>
        <!-- End Confirm delete popup -->

        <div class="control-block">
            <div class="move-label">: : :</div>

            <div class="name-component">
                <?php echo $object->name; ?>
            </div>

            <div class="show-hide">
                <label class="checkbox">
                    <input type="hidden" name="<?php echo $display['name']; ?>" value="off">
                    <input type="checkbox" class="show-hide-checkbox" name="<?php echo $display['name']; ?>" value="on"<?php echo $display['value']; ?>>
                    <div class="checkbox__text"></div>
                </label>
            </div>

            <div class="delete">
                <button class="button delete-component-button"><?php _e('Delete'); ?></button>
            </div>

            <div class="title-block">
                <input name="<?php echo $title['name']; ?>" placeholder="<?php _e('Title'); ?>" value="<?php echo $title['value']; ?>">
            </div>
        </div>

        <?php
    }

    private function componentFooter($object, $key, $value)
    {
        $component = [
            'name' => $this->name . '[' . $key . '][component]',
            'value' => $this->objectWithoutNamespace($object)
        ];
        $position = [
            'name' => $this->name . '[' . $key . '][position]',
            'value' => (isset($value['position']) && $value['position'] != '') ? $value['position'] : self::$placeholder
        ];
        $component_id = [
            'name' => $this->name . '[' . $key . '][component_id]',
            'value' => (isset($value['component_id']) && $value['component_id'] != '') ? $value['component_id'] : ''
        ];
        $component_class = [
            'name' => $this->name . '[' . $key . '][component_class]',
            'value' => (isset($value['component_class']) && $value['component_class'] != '') ? $value['component_class'] : ''
        ];
        ?>

        <div class="footer-block">
            <input type="text" placeholder="<?php _e('ID компонента'); ?>" name="<?php echo $component_id['name'];?>" value="<?php echo $component_id['value'];?>">
            <input type="text" placeholder="<?php _e('CLASS компонента'); ?>" name="<?php echo $component_class['name'];?>" value="<?php echo $component_class['value'];?>">

            <input type="hidden" name="<?php echo $component['name']; ?>" value="<?php echo $component['value']; ?>">
            <input type="hidden" name="<?php echo $position['name']; ?>" value="<?php echo $position['value']; ?>" class="position-component">
        </div>

        <?php
    }

    private function componentBody($object, $key, $value = null)
    {
        $component = $this->objectWithoutNamespace($object);

        echo '<div id="component-' . $key . '" class="' . $component . ' component-container" data-component-id="' . $key . '">';
        $this->componentHeader($object, $key, $value);
        $object->html($key, $this->name, $value);
        $this->componentFooter($object, $key, $value);
        echo '</div>';
    }

    private function getFilesComponent()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'components';

        if (!is_dir($dir)) return false;

        $files = scandir($dir);

        if (!$files || !is_array($files)) return false;

        $result = [];

        foreach ($files as $file)
        {
            if ($file == '.' || $file == '..' || is_dir($file)) continue;

            $result[] = $file;
        }

        return $result;
    }

    private function makeObject($name)
    {
        $objectName = __NAMESPACE__ . '\\components\\' . pathinfo($name)['filename'];

        if (class_exists($objectName))
        {
            return new $objectName();
        }

        return null;
    }

    private function objectWithoutNamespace($object)
    {
        $parts = explode('\\', get_class($object));

        return end($parts);
    }

    public static function beforeOutput($value)
    {
        return $value;
    }

    public static function beforeSave($value)
    {
        if (isset($value[self::$placeholder])) unset($value[self::$placeholder]);

        return $value;
    }
}