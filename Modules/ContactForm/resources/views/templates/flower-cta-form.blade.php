<?php
/*
  type: layout
  name: Flower form
  description: Flower form
*/
?>

<form class="col-xl-10 mw_form d-flex flex-wrap align-items-center justify-content-center mx-auto flower-cta-div-form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">

    <div class="mw-message-form-wrapper message-sent" id="msg<?php print $form_id ?>" style="display: none;">
        <span class="message-sent-icon"></span>
        <p class="text-success"><?php _lang("Your Email was sent successfully", 'template/big'); ?></p>
    </div>


       <div class="col-sm-8 col-12 my-md-0 my-2">
           <module type="custom_fields" for-id="{{ $params['id'] }}" data-for="module" template="bootstrap5_flex"
                   default-fields="[type=email,field_size=12,show_placeholder=true]" input_class=""/>
       </div>


        <div class="col-sm-4 col-12 my-md-0 my-2 text-end">
            <module type="btn" button_action="submit" button_style="btn-primary" button_size="justify-content-center text-end" button_text="Contact Now">
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
        <module type="users/terms" data-for="contact_form_default"/>
    <?php endif; ?>
</form>
