<?php $form_btn_title =  get_option('form_btn_title', $params['id']);
		if($form_btn_title == false) { 
		$form_btn_title = 'Reset password';
		}
 
 		 ?>
<? //$rand = uniqid(); ?>
<script  type="text/javascript">

mw.require('forms.js', true);


$(document).ready(function(){
	

	 
	 mw.$('#user_reset_password_form{rand}').submit(function() {

 
 mw.form.post(mw.$('#user_reset_password_form{rand}') , '<? print site_url('api') ?>/user_send_reset_password', function(){
	         mw.response('#form-holder{rand}',this);

	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages"]');
	 });

 return false;
 
 
 });
 
});
</script>

<div class="box-head">
  <h2>Reset your password</h2>
</div>
<div id="form-holder{rand}">
  <h4>Enter your username or email and we will send you password reset link</h4>
  <form id="user_reset_password_form{rand}" method="post" class="clearfix">
    <div class="control-group">
      <div class="controls">
        <input type="text" class="large-field"  name="username" placeholder="Email or Username">
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <div class="input-prepend" style="width: 100%;"> <span style="width: 100px;background: white" class="add-on"> <img class="mw-captcha-img" src="<? print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" /> </span>
          <input type="text" placeholder="Enter the text" class="mw-captcha-input" name="captcha">
        </div>
      </div>
    </div>
    <a class="btn btn-large pull-left" href="<? print curent_url(true,true); ?>">Back</a>
    <button type="submit" class="btn btn-large pull-right"><? print $form_btn_title ?></button>
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div>
