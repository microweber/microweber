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
        <div class="edit" data-field="contact_form_title" rel="contact_form_module" data-id="<?php print $params['id'] ?>">
            <h3 class="element contact-form-title"><?php _e("Leave a Message"); ?></h3>
        </div>
        <form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
            <module type="custom_fields" for-id="<?php print $params['id'] ?>" data-for="module" default-fields="name,email,message"/>

            <?php if ($show_newsletter_subscription == 'y' && !$newsletter_subscribed): ?>
                <div class="mw-flex-row">
                    <div class="mw-flex-col-md-12 mw-flex-col-sm-12 mw-flex-col-xs-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <label class="mw-ui-check" style="padding-top:0">
                                    <input type="checkbox" name="newsletter_subscribe" value="1" autocomplete="off"/> <span></span>
                                    <span><?php _e("Please email me your monthly news and special offers"); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($require_terms && $require_terms_when == 'b'): ?>
                <module type="users/terms" data-for="contact_form_default"/>
            <?php endif; ?>

            <div class="mw-flex-row">

                <div class="mw-flex-col-md-9 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <div class="control-group form-group">
                        <?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
                            <label class="custom-field-title"><?php _e("Enter Security code"); ?></label>
                            <div class="mw-ui-row captcha-holder" style="width: 262px;">
                                <div class="mw-ui-col">
                                    <module type="captcha"/>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mw-flex-col-md-3 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <label>&nbsp;</label>
                    <div class="control-group form-group">
                        <module type="btn" button_action="submit" button_style="mw-ui-btn pull-right" button_text="<?php _e("Send Message"); ?>"/>
                    </div>
                </div>

            </div>

        </form>
    </div>
    <div class="message-sent" id="msg<?php print $form_id ?>">
        <span class="message-sent-icon"></span>
        <p><?php _e("Your Email was sent successfully"); ?></p>
    </div>
</div>
