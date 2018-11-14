<?php

/*

  type: layout

  name: Default

  description: Default template for Contact form
  
  icon: dream.png


*/

?>


<script>
    mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css");
</script>

<div class="contact-form-container contact-form-template-dream">
    <div class="contact-form">
        <div class="edit" data-field="contact_form_title" rel="newsletter_module" data-id="<?php print $params['id'] ?>">
            <h3 class="element contact-form-title"><?php _e("Leave a Message"); ?></h3>
        </div>
        <form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">


            <module type="custom_fields" for-id="<?php print $params['id'] ?>" data-for="module" default-fields="name,email,message"/>

            <div class="control-group form-group">
                <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
                    <label class="custom-field-title"><?php _e("Enter Security code"); ?></label>
                    <div class="mw-ui-row captcha-holder" style="width: 220px; text-align: center">
                        <div class="mw-ui-col">
                            <module type="captcha"/>
                        </div>
                    </div>
                    <input type="submit" class="mw-ui-btn pull-right" value="<?php _e("Send Message"); ?>"/>
                <?php else: ?>
                    <input type="submit" class="mw-ui-btn pull-right" value="<?php _e("Send Message"); ?>"/>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <div class="message-sent" id="msg<?php print $form_id ?>">
        <span class="message-sent-icon"></span>
        <p><?php _e("Your Email was sent successfully"); ?></p>
    </div>
</div>
