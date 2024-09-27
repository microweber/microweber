<?php
/*
  type: layout
  name: Bootstrap 5 Template skin
  description: Bootstrap 5 Template skin
*/
?>

<form class="p-4 p-md-5 border rounded-3 bg-light mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
    <module type="custom_fields" template="bootstrap5" for-id="<?php print $params['id'] ?>" data-for="module" default-fields="email, number, checkbox"/>

    <div class="checkbox mb-3">
        <?php if ($require_terms && $require_terms_when == 'b'): ?>
            <module type="users/terms" data-for="contact_form_default"/>
        <?php endif; ?>
    </div>
    <module type="btn" button_action="submit" button_style="w-100 btn btn-primary" button_text="<?php _lang("Submit", 'template/big'); ?>"/>

    <hr class="my-4">
    <small class="text-muted">By clicking Sign up, you agree to the terms of use.</small>
</form>
