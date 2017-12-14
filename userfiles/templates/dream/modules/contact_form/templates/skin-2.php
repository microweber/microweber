<?php

/*

type: layout

name: Get Quote

description: Skin-2

*/

?>

<div class="alert alert-success margin-bottom-30" id="msg<?php print $form_id; ?>" style="display:none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Thank You!</strong> Your message successfully sent!
</div>

<form class="form--square form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
    <?php print csrf_form() ?>
    <input type="hidden" name="for" value="contact_form"/>
    <input type="hidden" name="for_id" value="contact_form"/>


    <h4 class="text-center edit" rel="module" field="contact-form-<?php print $params['id'] ?>">Or reach us right here&hellip;</h4>

    <div class="input-with-icon col-sm-6">
        <i class="icon-MaleFemale"></i>
        <input type="text" name="first_name" placeholder="Your Name" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Building"></i>
        <input type="text" name="company_position" placeholder="Company Position" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Email"></i>
        <input type="email" name="email" placeholder="Email Address" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Phone-2"></i>
        <input type="tel" name="phone" placeholder="Phone Number" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Suitcase"></i>
        <input type="text" name="company_name" placeholder="Company Name" required/>
    </div>

    <div class="input-with-icon col-sm-6">
        <i class="icon-Globe"></i>
        <input type="text" name="company_registration" placeholder="Country of registration" required/>
    </div>

    <div class="col-sm-12">
        <textarea name="message" placeholder="Your Message" rows="8" required></textarea>
    </div>
    <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
        <div class="col-sm-12">
            <module type="captcha"/>
            <br />
        </div>
    <?php endif; ?>
    <div class="col-sm-12">
        <button type="submit" class="btn btn--primary">Get Quote</button>
    </div>
</form>
