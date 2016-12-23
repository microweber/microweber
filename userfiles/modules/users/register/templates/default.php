<?php

/*

type: layout

name: Default

description: Default register template

*/

?>
<script  type="text/javascript">
    mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css");
    mw.require('forms.js', true);
    mw.require('url.js', true);
    $(document).ready(function(){
	    mw.$('#user_registration_form_holder').submit(function() {
            mw.form.post(mw.$('#user_registration_form_holder') , '<?php print site_url('api') ?>/user_register', function(){
                mw.response('#register_form_holder',this);
                if(typeof this.success !== 'undefined'){
                   mw.form.post(mw.$('#user_registration_form_holder') , '<?php print site_url('api') ?>/user_login', function(){
                      mw.load_module('users/login', '#<?php print $params['id'] ?>');
					  window.location.href = window.location.href;
                   });
                }
        	 });
        return false;
       });
    });
</script>

<div class="module-register well">
	<div class="box-head">
		<h2>
			<?php _e("New Registration or"); ?>
			<a href="<?php print login_url(); ?>">
			    <?php _e("Login"); ?>
			</a>
        </h2>
	</div>
	<div id="register_form_holder">
		<form id="user_registration_form_holder" method="post" class="reg-form-clearfix">

         <?php print csrf_form(); ?>




			<div class="control-group form-group">
				<div class="controls">
					<input type="text" class="large-field form-control"  name="email" placeholder="<?php _e("Email"); ?>">
				</div>
			</div>

			<?php if($form_show_first_name): ?>
				<div class="control-group form-group">
					<div class="controls">
						<input type="text" class="large-field form-control" name="first_name" placeholder="<?php _e("First name"); ?>">
					</div>
				</div>

			<?php endif; ?>

			<?php if($form_show_last_name): ?>
				<div class="control-group form-group">
					<div class="controls">
						<input type="text" class="large-field form-control" name="last_name" placeholder="<?php _e("Last name"); ?>">
					</div>
				</div>

			<?php endif; ?>

			<div class="control-group form-group">
				<div class="controls">
					<input type="password" class="large-field form-control" name="password" placeholder="<?php _e("Password"); ?>">
				</div>
			</div>

			<?php if($form_show_password_confirmation): ?>
			<div class="control-group form-group">
				<div class="controls">
					<input type="password" class="large-field form-control" name="password2" placeholder="<?php _e("Repeat password"); ?>">
				</div>
			</div>
			<?php endif; ?>






            <div class="mw-ui-row vertical-middle captcha-row">
              <div class="mw-ui-col">
                 <div class="mw-captcha-image-holder"><img class="mw-captcha-img" src="<?php print api_link('captcha') ?>" onclick="mw.tools.refresh_image(this);" /></div>
              </div>
              <div class="mw-ui-col">
                 <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="form-control mw-captcha-input" name="captcha">
              </div>
            </div>

            <div class="alert" style="margin: 0;display: none;"></div>
			<div class="social-login">

                    <?php
                        # Login Providers
                        $facebook = get_option('enable_user_fb_registration','users') =='y';
                        $twitter = get_option('enable_user_twitter_registration','users') =='y';
                        $google = get_option('enable_user_google_registration','users') =='y';
                        $windows = get_option('enable_user_windows_live_registration','users') =='y';
                        $github = get_option('enable_user_github_registration','users') =='y';
                        if($facebook or $twitter or $google or $windows or $github){
                           $have_social_login = true;
                        }
                        else{ $have_social_login = false;  }
                    ?>
                    <?php if($have_social_login){ ?><h5><?php _e("Login with"); ?>:</h5><?php } ?>
                    <?php if($have_social_login){ ?><ul><?php } ?>
                        <?php if($facebook): ?>
                        <li><a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="mw-signin-with-facebook">Facebook login</a></li>
                        <?php endif; ?>
                        <?php if($twitter): ?>
                        <li><a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="mw-signin-with-twitter">Twitter login</a></li>
                        <?php endif; ?>
                        <?php if($google): ?>
                        <li><a href="<?php print api_link('user_social_login?provider=google') ?>" class="mw-signin-with-google">Google login</a></li>
                        <?php endif; ?>
                        <?php if($windows): ?>
                        <li><a href="<?php print api_link('user_social_login?provider=live') ?>" class="mw-signin-with-live">Windows login</a></li>
                        <?php endif; ?>
                        <?php if($github): ?>
                        <li><a href="<?php print api_link('user_social_login?provider=github') ?>" class="mw-signin-with-github">Github login</a></li>
                        <?php endif; ?>
                    <?php if($have_social_login){ ?></ul><?php } ?>
            </div>
			<button type="submit" class="btn btn-default pull-right"><?php print $form_btn_title ?></button>
			<div style="clear: both"></div>
		</form>

	</div>
</div>
