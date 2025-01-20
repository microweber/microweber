<?php
/*
  type: layout
  name: Skin-2
  description: Skin-2
*/
?>


<form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
    <div class="mw-message-form-wrapper message-sent" id="msg<?php print $form_id ?>" style="display: none">
        <span class="message-sent-icon"></span>
        <p class="text-success"><?php _lang("Your Email was sent successfully", 'template/big'); ?></p>
    </div>

    <module type="custom_fields" template="bootstrap5" for-id="{{ $params['id'] }}" data-for="module"
            default-fields="Email[type=email,field_size=12,show_placeholder=true], Name[type=text,field_size=12,show_placeholder=true]" input_class="form-control"/>

    <?php if ($require_terms && $require_terms_when == 'b'): ?>
        <module type="users/terms" data-for="contact_form_default"/>
    <?php endif; ?>

    <br><br>
    <module type="btn" button_action="submit" button_style="btn-primary" button_size="btn-block w-100 justify-content-center" button_text="Send"/>
</form>
