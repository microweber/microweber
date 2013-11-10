<?php

/*

type: layout

name: Default

description: Default register template

*/
 

  
   ?>
<script  type="text/javascript">

mw.require('forms.js', true);


$(document).ready(function(){



	 mw.$('#user_registration_form_holder').submit(function() {


 mw.form.post(mw.$('#user_registration_form_holder') , '<?php print site_url('api') ?>/user_register', function(){


        mw.response('#form-holder_holder',this);

        if(typeof this.success !== 'undefined'){
         mw.form.post(mw.$('#user_registration_form_holder') , '<?php print site_url('api') ?>/user_login', function(){
            mw.load_module('users/login', '#<?php print $params['id'] ?>');
         });
        }

	 });

 return false;


 });

});




</script>

<div class="well">
	<div class="box-head">
		<h2>
			<?php _e("New Registration or"); ?>
			<a href="javascript:mw.load_module('users/login', '#<?php print $params['id'] ?>');">
			<?php _e("Login"); ?>
			</a></h2>
	</div>
	<div id="form-holder_holder">
		<form id="user_registration_form_holder" method="post" class="clearfix">
			<div class="control-group">
				<div class="controls">
					<input type="text" class="large-field"  name="email" placeholder="<?php _e("Email"); ?>">
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<input type="password" class="large-field" name="password" placeholder="<?php _e("Password"); ?>">
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<div class="input-prepend" style="width: 100%;"> <span style="width: 100px;background: white" class="add-on"> <img class="mw-captcha-img" src="<?php print api_link('captcha') ?>" onclick="mw.tools.refresh_image(this);" /> </span>
						<input type="text" placeholder="<?php _e("Enter the text"); ?>" class="mw-captcha-input" name="captcha">
					</div>
				</div>
			</div>
			<div class="social-login">
			 
				<?php if(get_option('enable_user_fb_registration','users') =='y'): ?>
				<a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="mw-social-ico-facebook"></a>
				<?php $have_social_login = true; ?>
				<?php endif; ?>
				<?php if(get_option('enable_user_twitter_registration','users') =='y'): ?>
				<a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="mw-social-ico-twitter"></a>
				<?php $have_social_login = true; ?>
				<?php endif; ?>
				<?php if(get_option('enable_user_google_registration','users') =='y'): ?>
				<a href="<?php print api_link('user_social_login?provider=google') ?>" class="mw-social-ico-google"></a>
				<?php $have_social_login = true; ?>
				<?php endif; ?>
				<?php if(get_option('enable_user_windows_live_registration','users') =='y'): ?>
				<a href="<?php print api_link('user_social_login?provider=live') ?>" class="mw-social-ico-live"></a>
				<?php $have_social_login = true; ?>
				<?php endif; ?>
				<?php if(get_option('enable_user_github_registration','users') =='y'): ?>
				<a href="<?php print api_link('user_social_login?provider=github') ?>" class="mw-social-ico-github"></a>
				<?php $have_social_login = true; ?>
				<?php endif; ?>
			</div>
			<button type="submit" class="btn btn-large pull-right"><?php print $form_btn_title ?></button>
			<div style="clear: both"></div>
		</form>
		<div class="alert" style="margin: 0;display: none;"></div>
	</div>
</div>
