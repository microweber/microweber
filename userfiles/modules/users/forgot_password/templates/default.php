<div class="well">
<div class="box-head">
  <h2><a href="javascript:mw.load_module('users/register', '#<?php print $params['id'] ?>');"><?php _e("New Registration"); ?></a> or <a href="javascript:mw.load_module('users/login', '#<?php print $params['id'] ?>');"><?php _e("Login"); ?></a></h2>
</div>
<div id="form-holder{rand}">
<h4><?php _e("Enter your username or email"); ?></h4>
  <form id="user_forgot_password_form{rand}" method="post" class="clearfix">
    <div class="control-group form-group">
      <div class="controls">
        <input type="text" class="large-field form-control"  name="username" placeholder="<?php _e("Email or Username"); ?>">
      </div>
    </div>



    <div class="mw-ui-row vertical-middle captcha-row">
        <div class="mw-ui-col">
            <img class="mw-captcha-img" src="<?php print api_link('captcha') ?>" onclick="mw.tools.refresh_image(this);" />
        </div>
        <div class="mw-ui-col">
            <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="mw-captcha-input" name="captcha">
        </div>
    </div>








    <button type="submit" class="btn btn-default btn-large pull-right"><?php print $form_btn_title ?></button>
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div></div>