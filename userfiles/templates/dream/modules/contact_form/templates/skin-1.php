<div class="alert alert-success margin-bottom-30" id="msg<?php print $form_id; ?>" style="display:none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Thank You!</strong> Your message successfully sent!
</div>

<div class="form-subscribe-1 boxed boxed--lg bg--white box-shadow-wide">
    <div class="subscribe__title text-center edit" rel="module" field="module-<?php print $params['id'] ?>">
        <h4>Keep Informed</h4>
        <p class="lead">
            Subscribe for free resources and news updates.
        </p>
    </div>

    <form class="" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
        <?php print csrf_form() ?>
        <input type="hidden" name="for" value="contact_form"/>
        <input type="hidden" name="for_id" value="contact_form"/>

        <div class="input-with-icon">
            <i class="icon icon-Male-2"></i>
            <input id="fieldName" name="first_name" placeholder="<?php _e('Your Name'); ?>" type="text">
        </div>
        <div class="input-with-icon">
            <i class="icon icon-Mail-2"></i>
            <input class="validate-required validate-email" id="fieldEmail" name="email" placeholder="<?php _e('Email Address'); ?>" type="email" required="">
        </div>

        <div class="input-with-icon">
            <i class="icon icon-Phone-2"></i>
            <input type="text" name="phone" id="fieldPhone" placeholder="<?php _e('Phone'); ?>"/>
        </div>

        <div class="input-with-icon">
            <i class="icon icon-message"></i>
            <textarea name="message" id="fieldMessage" placeholder="Your Message" rows="2" style="background: #f8f8f8; border-radius: 15px;"></textarea>
        </div>

        <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
            <module type="captcha" template="skin-1"/>
            <br/>
        <?php endif; ?>

        <input type="submit" clsas="btn" value="<?php _e('Submit Form'); ?>"/>
    </form>
</div>