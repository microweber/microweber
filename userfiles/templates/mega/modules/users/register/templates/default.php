<?php

/*

type: layout

name: Default

description: Default register template

*/
 


   ?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>
<?php if($user != false): ?>
<module type="users/profile"  />
<?php elseif(isset($_GET['reset_password_link'])): ?>
<module type="users/forgot_password" />
<?php else:  ?>
<?php $form_btn_title =  get_option('form_btn_title', $params['id']);
		if($form_btn_title == false) {
		$form_btn_title = 'Register';
		}


$enable_user_fb_registration =  get_option('enable_user_fb_registration', $params['id']);
if($enable_user_fb_registration == 'y') {
$enable_user_fb_registration = true;
} else {
$enable_user_fb_registration = false;
}

if($enable_user_fb_registration == true){
	$enable_user_fb_registration_site =  get_option('enable_user_fb_registration', 'users');
	if($enable_user_fb_registration_site == 'y') {
	$enable_user_fb_registration = true;

	$fb_app_id  = get_option('fb_app_id','users');
	$fb_app_secret  = get_option('fb_app_secret','users');

	if($fb_app_id != false){
	$fb_app_id = trim($fb_app_id);
	}

	if($fb_app_secret != false){
	$fb_app_secret = trim($fb_app_secret);
	}



	if($fb_app_id == ''){
	$enable_user_fb_registration = false;
	}

	} else {
	$enable_user_fb_registration = false;

	}
}



		 ?>
<?php //$rand = uniqid(); ?>
<script  type="text/javascript">

mw.require('forms.js', true);


$(document).ready(function(){

	 mw.$('#user_registration_form{rand}').submit(function() {
           mw.form.post(mw.$('#user_registration_form{rand}') , '<?php print site_url('api') ?>/user_register', function(){
                  mw.response('#form-holder{rand}',this);
                  if(typeof this.success !== 'undefined'){
                   mw.form.post(mw.$('#user_registration_form{rand}') , '<?php print site_url('api') ?>/user_login', function(){
                      mw.load_module('users/login', '#<?php print $params['id'] ?>');
                   });
                  }
          	 });
           return false;
     });

     mw.$('[autofocus]').focus();

});




</script>

<div class="box-head">
  <h2><?php _e("New Registration or"); ?> <a  class="blue" href="javascript:mw.load_module('users/login', '#<?php print $params['id'] ?>', false, {template:'mega'});"><?php _e("Login"); ?></a></h2>
</div>
<hr>
<div id="form-holder{rand}">
  <form id="user_registration_form{rand}" method="post" class="clearfix">
    <div class="control-group">
      <div class="controls">
        <input type="text" class="box" autofocus  name="email" placeholder="<?php _e("Email"); ?>">
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <input type="password" class="box" name="password" placeholder="<?php _e("Password"); ?>">
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <div class="box box-feld">
        <div class="box-content">
            <img class="mw-captcha-img" src="<?php print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" />
          <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="invisible-field" name="captcha">
        </div>
        </div>
      </div>
    </div>
    <div class="social-login">
      <label><?php _e("Login with"); ?></label>
      <?php if(get_option('enable_user_fb_registration','users') =='y'): ?>
      <a href="<?php print site_url('api/user_social_login?provider=facebook') ?>" class="mw-social-ico-facebook"></a>
      <?php $have_social_login = true; ?>
      <?php endif; ?>
      <?php if(get_option('enable_user_twitter_registration','users') =='y'): ?>
      <a href="<?php print site_url('api/user_social_login?provider=twitter') ?>" class="mw-social-ico-twitter"></a>
      <?php $have_social_login = true; ?>
      <?php endif; ?>
      <?php if(get_option('enable_user_google_registration','users') =='y'): ?>
      <a href="<?php print site_url('api/user_social_login?provider=google') ?>" class="mw-social-ico-google"></a>
      <?php $have_social_login = true; ?>
      <?php endif; ?>
      <?php if(get_option('enable_user_windows_live_registration','users') =='y'): ?>
      <a href="<?php print site_url('api/user_social_login?provider=live') ?>" class="mw-social-ico-live"></a>
      <?php $have_social_login = true; ?>
      <?php endif; ?>
      <?php if(get_option('enable_user_github_registration','users') =='y'): ?>
      <a href="<?php print site_url('api/user_social_login?provider=github') ?>" class="mw-social-ico-github"></a>
      <?php $have_social_login = true; ?>
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-large pull-right"><?php print $form_btn_title ?></button>
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div>
<?php endif; ?>

