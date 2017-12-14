<?php

/*

type: layout

name: Default

description: Default contact form

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

    <div class="input-with-icon col-sm-12">
        <i class="icon-MaleFemale"></i>
        <input class="validate-required" type="text" name="first_name" placeholder="Your Name"/>
    </div>
    <div class="input-with-icon col-sm-6">
        <i class="icon-Email"></i>
        <input class="validate-required validate-email" type="email" name="email" placeholder="Email Address"/>
    </div>
    <div class="input-with-icon col-sm-6">
        <i class="icon-Phone-2"></i>
        <input type="tel" name="phone" placeholder="Phone Number"/>
    </div>
    <div class="col-sm-12">
        <textarea class="validate-required" name="message" placeholder="Your Message" rows="8"></textarea>
    </div>
    <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
        <div class="col-sm-12">
            <module type="captcha"/>
            <br />
        </div>
    <?php endif; ?>
    <div class="col-sm-12">
        <button type="submit" class="btn btn--primary">Send</button>
    </div>
</form>
