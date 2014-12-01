<?php

/*

  type: layout

  name: Subscribe

  description: Subscribe form
  


*/

?>




<script>
    mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css");
</script>
 
<div class="contact-form-container contact-form-template-footer">
    <div class="contact-form">
        <div class="edit" data-field="contact_form_title" rel="newsletter_module" data-id="<?php print $params['id'] ?>" style="min-height:0;">
          <h4><?php _e("Subscribe"); ?></h4>
        </div>
        <form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post" >
        
         <?php print csrf_form($params['id']); ?>
            <module type="custom_fields" data-id="<?php print $params['id'] ?>" data-for="module"  default-fields="email"   />
            <div class="control-group form-group">
                <?php if(get_option('disable_captcha', $params['id']) !='y'): ?>
                    <label class="custom-field-title"><?php _e("Enter Security code"); ?></label>
                    <div class="mw-ui-row captcha-holder">

                        <div class="mw-ui-col">
                          <img onclick="mw.tools.refresh_image(this);" class="mw-captcha-img" id="captcha-<?php print $form_id; ?>" src="<?php print api_link('captcha') ?>" />
                        </div>
                        <div class="mw-ui-col">
                            <input name="captcha" type="text" required class="mw-ui-field mw-captcha-input"/>
                        </div>

                    </div>
                    <input type="submit" class="mw-ui-btn mw-ui-btn-invert pull-right" style="margin-left: 12px;"  value="<?php _e("Subscribe"); ?>" />
                <?php else:  ?>
                    <input type="submit" class="mw-ui-btn mw-ui-btn-invert pull-right"  value="<?php _e("Subscribe"); ?>" />
                <?php endif; ?>
            </div>
        </form>
    </div>
    <div class="message-sent" id="msg<?php print $form_id ?>">
        <span class="message-sent-icon"></span>
        <p>You have been successfully subscribed</p>
    </div>
</div>
