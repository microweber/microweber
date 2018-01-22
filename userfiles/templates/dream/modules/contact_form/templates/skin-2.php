<?php

/*

type: layout

name: Get Quote

description: Skin-2

*/

?>

<div class="alert alert-success margin-bottom-30" id="msg<?php print $form_id; ?>" style="display:none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong><?php _lang("Thank You", "templates/dream"); ?>!</strong> <?php _lang("Your message successfully sent", "templates/dream"); ?>!
</div>

<form class="form--square form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
    <?php print csrf_form() ?>
    <input type="hidden" name="for" value="get_a_quote"/>
    <input type="hidden" name="for_id" value="get_a_quote"/>


    <h4 class="text-center edit" rel="module" field="contact-form-<?php print $params['id'] ?>"><?php _lang("Or reach us right here", "templates/dream"); ?>&hellip;</h4>

    <div class="input-with-icon col-sm-6">
        <i class="icon-MaleFemale"></i>
        <input type="text" name="first_name" placeholder="<?php _lang("Your Name", "templates/dream"); ?>" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Building"></i>
        <input type="text" name="company_position" placeholder="<?php _lang("Company Position", "templates/dream"); ?>" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Email"></i>
        <input type="email" name="email" placeholder="<?php _lang("Email Address", "templates/dream"); ?>" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Phone-2"></i>
        <input type="tel" name="phone" placeholder="<?php _lang("Phone Number", "templates/dream"); ?>" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Suitcase"></i>
        <input type="text" name="company_name" placeholder="<?php _lang("Company Name", "templates/dream"); ?>" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Globe"></i>
        <input type="text" name="company_registration" placeholder="<?php _lang("Country of registration", "templates/dream"); ?>" required/>
    </div>

    <div class="col-sm-12">
        <textarea name="message" placeholder="<?php _lang("Your Message", "templates/dream"); ?>" rows="8" required></textarea>
    </div>
    <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
        <div class="col-sm-12">
            <module type="captcha"/>
            <br />
        </div>
    <?php endif; ?>
    <div class="col-sm-12">
        <button type="submit" class="btn btn--primary"><?php _lang("Get Quote", "templates/dream"); ?></button>
    </div>
</form>
