<?php

/*

  type: layout

  name: Dark

  description: Dark theme for your website

  icon: dark.png

  version: 0.2

*/

?>


<script>
    mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css", true);
</script>

<div class="contact-form-container contact-form-template-dark">
    <div class="contact-form">
        <div class="edit" field="contact_form_title" rel="newsletter_module" data-id="<?php print $params['id'] ?>">
            <h3 class="element contact-form-title"><?php _e("Leave a Message"); ?></h3>
        </div>
        <form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">

            <module type="custom_fields" for-id="<?php print $params['id'] ?>" data-for="module" default-fields="<?php print $default_fields; ?>"/>

        <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
            <div class="control-group form-group">
                <label><?php _e("Security code"); ?></label>
                <div class="mw-ui-row captcha-holder">
                    <div class="mw-ui-col">
                        <module type="captcha"/>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <module type="btn" button_action="submit" button_style="btn btn-default" button_text="<?php print $button_text; ?>"  />

        </form>
    </div>
    <div class="message-sent" id="msg<?php print $form_id ?>">
        <span class="message-sent-icon message-sent-icon-orange"></span>
        <p><?php _e("Your Email was sent successfully"); ?> </p>
    </div>
</div>
