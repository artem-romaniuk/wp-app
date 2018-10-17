<?php

/* Callback form Handler */
add_action( 'admin_post_nopriv_callback_form', 'callbackHandler' );
add_action( 'admin_post_callback_form', 'callbackHandler' );

function callbackHandler() {

    //...

}

/* Form in frontend */

//<form action="<php echo appConfig('post_handler', '/'); >">
//    <?php wp_nonce_field('callback_action','callback_field'); >
//    <input type="hidden" name="action" value="callback_form">
//
/*    <input type="text" placeholder="<php echo trans('Ваше имя'); ?>" name="name" class="inpt name">*/
/*    <input type="text" placeholder="<php echo trans('Ваш телефон'); ?>" name="phone" class="inpt phone">*/
//    <button type="submit" class="inpt sub fill-right"><php echo trans('Отправить заявку'); ></button>
//</form>

/* End Form in frontend */

/* Callback form Handler */