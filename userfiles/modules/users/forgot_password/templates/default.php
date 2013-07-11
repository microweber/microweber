
<div class="box-head">
  <h2><a href="javascript:mw.load_module('users/register', '#<?php print $params['id'] ?>');">New Registration</a> or <a href="javascript:mw.load_module('users/login', '#<?php print $params['id'] ?>');">Login</a></h2>
</div>
<div id="form-holder{rand}">
<h4>Enter your username or email</h4>
  <form id="user_forgot_password_form{rand}" method="post" class="clearfix">








    <div class="control-group">
      <div class="controls">
        <input type="text" class="large-field"  name="username" placeholder="Email or Username">
      </div>
    </div>







    <div class="control-group">
      <div class="controls">
        <div class="input-prepend" style="width: 100%;"> <span style="width: 100px;background: white" class="add-on"> <img class="mw-captcha-img" src="<?php print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" /> </span>
          <input type="text" placeholder="Enter the text" class="mw-captcha-input" name="captcha">
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-large pull-right"><?php print $form_btn_title ?></button>
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div>