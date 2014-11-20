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
      <h3>Write us a letter</h3>
      <hr>
  </div>
  <form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post" >
           

    <module type="custom_fields" data-id="<?php print $params['id'] ?>" data-for="module"  default-fields="name,email,message"   />
    <?php if(get_option('disable_captcha', $params['id']) !='y'): ?>
      <div class="control-group form-group">
        <label><?php _e("Security code"); ?></label>
        <div class="mw-ui-row captcha-holder">
              <div class="mw-ui-col">
                  <input name="captcha" type="text" required class="mw-captcha-input"/>
              </div>
              <div class="mw-ui-col">
                <img onclick="mw.tools.refresh_image(this);" class="mw-captcha-img" id="captcha-<?php print $form_id; ?>" src="<?php print api_link('captcha') ?>" />
              </div>
              <div class="mw-ui-col">
                 <span class="ico irefresh" onclick="mw.tools.refresh_image(mwd.getElementById('captcha-<?php print $form_id; ?>'));"></span>
              </div>
          </div>
      </div>
    <?php  endif;?>
    <input type="submit" class="btn btn-default"  value="<?php _e("Submit"); ?>" />
  </form>

</div>



