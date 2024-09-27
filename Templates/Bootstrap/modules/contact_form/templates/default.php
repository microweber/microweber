<?php
/*
  type: layout
  name: Default
  description: Default
*/
?>


<form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
    <div class="message-sent" id="msg<?php print $form_id ?>" style="display: none">
        <span class="message-sent-icon"></span>
        <p class="text-success"><?php _lang("Your Email was sent successfully", 'template/big'); ?></p>
    </div>

    <module type="custom_fields" template="bootstrap4" for-id="<?php print $params['id'] ?>" data-for="module" default-fields="name,email"/>

    <!--    --><?php //if ($show_newsletter_subscription == 'y' && !$newsletter_subscribed): ?>
    <!--        <div class="form-group">-->
    <!--            <div class="custom-control custom-checkbox">-->
    <!--                <label class="mw-ui-check" style="padding-top:0">-->
    <!--                    <input type="checkbox" name="newsletter_subscribe" value="1" autocomplete="off"/> <span></span>-->
    <!--                    <span>--><?php //_lang("Please email me your monthly news and special offers", 'template/big'); ?><!--</span>-->
    <!--                </label>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    --><?php //endif; ?>

    <?php if ($require_terms && $require_terms_when == 'b'): ?>
        <module type="users/terms" data-for="contact_form_default"/>
    <?php endif; ?>

    <div class="col-12">
        <module type="btn" button_action="submit" button_style="btn-primary" button_size="btn btn-primary d-flex justify-content-center btn-block w-100 text-center" button_text="<?php _lang("Submit", 'template/big'); ?>"/>
    </div>
</form>
