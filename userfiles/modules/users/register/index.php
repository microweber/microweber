  
<? $user = user_id(); ?>
<? $have_social_login = false; ?>
<? if($user != false): ?>
<module type="users/profile" />
<? elseif(isset($_GET['reset_password_link'])): ?>
<module type="users/forgot_password" />
<? else:  ?>
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
<? //$rand = uniqid(); ?>
<script  type="text/javascript">

mw.require('forms.js', true);


$(document).ready(function(){
	

	 
	 mw.$('#user_registration_form{rand}').submit(function() {

 
 mw.form.post(mw.$('#user_registration_form{rand}') , '<? print site_url('api') ?>/register_user', function(){
	         mw.response('#form-holder{rand}',this);

	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages"]');
	 });

 return false;
 
 
 });
 
});
</script>

<div class="box-head">
  <h2>New Registration or <a href="javascript:mw.load_module('users/login', '#<? print $params['id'] ?>');">Login</a></h2>
</div>
<div id="form-holder{rand}">
  <form id="user_registration_form{rand}" method="post" class="clearfix">
    <div class="control-group">
      <div class="controls">
        <input type="text" class="large-field"  name="email" placeholder="Email">
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <input type="password" class="large-field" name="password" placeholder="Password">
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <div class="input-prepend" style="width: 100%;"> <span style="width: 100px;background: white" class="add-on"> <img class="mw-captcha-img" src="<? print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" /> </span>
          <input type="text" placeholder="Enter the text" class="mw-captcha-input" name="captcha">
        </div>
      </div>
    </div>
    <div class="social-login">
      <label>Login with</label>
      <? if(get_option('enable_user_fb_registration','users') =='y'): ?>
      <a href="<? print site_url('api/user_social_login?provider=facebook') ?>" class="mw-social-ico-facebook"></a>
      <? $have_social_login = true; ?>
      <? endif; ?>
      <? if(get_option('enable_user_twitter_registration','users') =='y'): ?>
      <a href="<? print site_url('api/user_social_login?provider=twitter') ?>" class="mw-social-ico-twitter"></a>
      <? $have_social_login = true; ?>
      <? endif; ?>
      <? if(get_option('enable_user_google_registration','users') =='y'): ?>
      <a href="<? print site_url('api/user_social_login?provider=google') ?>" class="mw-social-ico-google"></a>
      <? $have_social_login = true; ?>
      <? endif; ?>
      <? if(get_option('enable_user_windows_live_registration','users') =='y'): ?>
      <a href="<? print site_url('api/user_social_login?provider=live') ?>" class="mw-social-ico-live"></a>
      <? $have_social_login = true; ?>
      <? endif; ?>
      <? if(get_option('enable_user_github_registration','users') =='y'): ?>
      <a href="<? print site_url('api/user_social_login?provider=github') ?>" class="mw-social-ico-github"></a>
      <? $have_social_login = true; ?>
      <? endif; ?>
    </div>
    <button type="submit" class="btn btn-large pull-right"><? print $form_btn_title ?></button>
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div>
<? endif; ?>
