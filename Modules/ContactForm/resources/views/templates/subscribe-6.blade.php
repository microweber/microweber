<?php
/*
  type: layout
  name: Subscribe 6
  description: Subscribe 6
*/
?>


<form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">

    <div class="mw-message-form-wrapper message-sent" id="msg<?php print $form_id ?>" style="display: none;">
        <span class="message-sent-icon"></span>
        <p class="text-success"><?php _lang("Your Email was sent successfully", 'template/big'); ?></p>
    </div>

    <div class="row justify-content-center align-items-end">
        <div class="col-md-8">
            <module type="custom_fields" for-id="{{ $params['id'] }}" data-for="module" template="bootstrap5"
                default-fields="First Name[type=text,field_size=6,show_placeholder=true], Email[type=email,field_size=6,show_placeholder=true]" input_class="form-control">
        </div>

        <div class="col-md-4 mb-2">
            <module type="btn" button_action="submit" button_style="btn-primary" button_size="w-100 justify-content-center" button_text="<?php _lang("Join", 'template/big'); ?>">
        </div>
    </div>


    <?php if ($show_newsletter_subscription == 'y' && !$newsletter_subscribed): ?>
        <div class="form-group">
            <div class="custom-control custom-checkbox my-2">
                <label class="mw-ui-check" style="padding-top:0">
                    <input type="checkbox" name="newsletter_subscribe" value="1" autocomplete="off"/> <span></span>
                    <span><?php _lang("Please email me your monthly news and special offers", 'template/big'); ?></span>
                </label>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($require_terms && $require_terms_when == 'b'): ?>
        <module type="users/terms" data-for="contact_form_default">
    <?php endif; ?>
</form>
