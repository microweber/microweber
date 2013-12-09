<div class="well">
<div class="box-head">
  <h3 style="font-weight: normal;font-size: 17px;padding-bottom: 7px;"><?php _e("Enter your username or email"); ?></h3>
</div>
<div id="form-holder{rand}">

  <form id="user_forgot_password_form{rand}" method="post" class="clearfix">
    <div class="mw-ui-field-holder">

        <input type="text" class="mw-ui-field"  name="username" placeholder="<?php _e("Enter Email or Username"); ?>">

    </div>

    <div class="mw-ui-field-holder">

          <div class="mw-ui-field mw-ico-field">
              <img class="mw-captcha-img" src="<?php print api_link('captcha') ?>" onclick="mw.tools.refresh_image(this);" />
              <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="mw-ui-invisible-field" name="captcha">
          </div>
    </div>
    <div class="mw-ui-field-holder" style="margin: auto; width: 286px;">
        <button type="submit" id="submit" class="mw-ui-btn right"><?php print $form_btn_title; ?></button>
    </div>

    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div></div>