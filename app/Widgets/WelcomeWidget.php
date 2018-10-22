<?php

class WelcomeWidget extends \WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'welcome-widget',
            'Приветствия',
            ['description' => 'Виджет с приветствием']
        );

    }


    function widget($args, $instance)
    {
        echo $args['before_widget']; ?>

        <section id="welcome-section">
            <div class="inner-container container">
                <div class="l-sec col-md-7">
                    <div class="ravis-title-t-1">
                        <div class="title"><span><?php echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title']; ?></span></div>
                        <div class="sub-title"><?php echo $instance['subtitle']; ?></div>
                    </div>
                    <div class="content">
                        <?php echo $instance['description']; ?>
                    </div>
                    <a href="<?php echo esc_url($instance['link']); ?>" class="ravis-btn btn-type-2"><?php echo $instance['link_text']; ?></a>
                </div>
                <div class="r-sec col-md-5">
                    <img src="<?php echo esc_url($instance['image_src']); ?>" alt="Colosseum Hotel">
                </div>
            </div>
        </section>

        <?php echo $args['after_widget'];
    }


    function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['subtitle'] = (!empty($new_instance['subtitle'])) ? strip_tags($new_instance['subtitle']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? strip_tags($new_instance['description']) : '';
        $instance['link'] = (!empty($new_instance['link'])) ? strip_tags($new_instance['link']) : '';
        $instance['link_text'] = (!empty($new_instance['link_text'])) ? strip_tags($new_instance['link_text']) : '';
        $instance['image_src'] = (!empty($new_instance['image_src'])) ? strip_tags($new_instance['image_src']) : '';

        return $instance;
    }


    function form($instance)
    {
        $title = @$instance['title'] ?: 'Заголовок';
        $subtitle = @$instance['subtitle'] ?: '';
        $description = @$instance['description'] ?: '';
        $link = @$instance['link'] ?: '#';
        $link_text = @$instance['link_text'] ?: '';
        $image_src = @$instance['image_src'] ?: '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок: '); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'subtitle'); ?>"><?php _e('Подзаголовок: '); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description'); ?>"><?php _e('Описание: '); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo esc_attr($description); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link'); ?>"><?php _e('Ссылка на страницу: '); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_url($link); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link_text'); ?>"><?php _e('Текст кнопки: '); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_attr($link_text); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_src'); ?>"><?php _e('Ссылка изображения: '); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_src'); ?>" name="<?php echo $this->get_field_name('image_src'); ?>" type="text" value="<?php echo esc_url($image_src); ?>">
        </p>
        <?php
    }

}