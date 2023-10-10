<?php

/*

type: layout

name: Basic

description: Basic contact form

*/

?>

<script>
    mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css", true);
</script>

<div class="contact-form-container contact-form-template-basic">
    <div class="edit" data-field="contact_form_title" rel="newsletter_module" data-id="<?php print $params['id'] ?>">
        <h3><?php _e("Write us a letter"); ?></h3>
        <hr>
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

        <module type="btn" button_action="submit" button_style="btn btn-default" button_text="<?php _e("Submit"); ?>"  />

    </form>

</div>
