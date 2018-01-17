<?php

/*

type: layout

name: Default

description: Default contact form

*/

?>

<div class="alert alert-success margin-bottom-30" id="msg<?php print $form_id; ?>" style="display:none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong><?php _lang("Thank You", "modules/contact_form"); ?>!</strong> <?php _lang("Your message successfully sent", "modules/contact_form"); ?>!
</div>

<form class="form--square form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
    <?php print csrf_form() ?>
    <input type="hidden" name="for" value="default_contact_form"/>
    <input type="hidden" name="for_id" value="default_contact_form"/>


    <h4 class="text-center edit" rel="module" field="contact-form-<?php print $params['id'] ?>"><?php _lang("Or reach us right here", "modules/contact_form"); ?>&hellip;</h4>

    <div class="input-with-icon col-sm-12">
        <i class="icon-MaleFemale"></i>
        <input class="validate-required" type="text" name="first_name" placeholder="<?php _lang("Your Name", "modules/contact_form"); ?>"/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Email"></i>
        <input class="validate-required validate-email" type="email" name="email" placeholder="<?php _lang("Email Address", "modules/contact_form"); ?>"/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Phone-2"></i>
        <input type="tel" name="phone" placeholder="Phone Number"/>
    </div>

    <div class="col-xs-12">
        <module type="custom_fields" data-id="<?php print $params['id'] ?>" data-for="module" template="skin-1" default-fields="" input_class="form-control"/>
    </div>

    <div class="col-sm-12">
        <textarea class="validate-required" name="message" placeholder="<?php _lang("Your Message", "modules/contact_form"); ?>" rows="8"></textarea>
    </div>

    <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
        <div class="col-sm-12">
            <module type="captcha"/>
            <br/>
        </div>
    <?php endif; ?>

    <div class="col-sm-12">
        <button type="submit" class="btn btn--primary"><?php _e("Send") ?></button>
    </div>
</form>
