<?php

/*

type: layout

name: Default

description: Default register template

*/

?>
<script  type="text/javascript">
    mw.moduleCSS("<?php print MW_MODULES_URL; ?>users/users_modules.css");
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
			<?php _e("New Registration"); ?>
        </h2>
	</div>
	<div id="register_form_holder">
		<form id="user_registration_form_holder" method="post" class="clearfix">
         <?php print csrf_form(); ?>
         <?php if($form_show_first_name): ?>
            <div class="control-group form-group">
				<div class="controls">
					<input type="text" class="mw-ui-field mw-ui-field-big w100" name="first_name" placeholder="<?php _e("First name"); ?>" >
				</div>
			</div>
         <?php endif; ?>
         <?php if($form_show_last_name): ?>
            <div class="control-group form-group">
				<div class="controls">
					<input type="text" class="mw-ui-field mw-ui-field-big w100" name="last_name" placeholder="<?php _e("Last name"); ?>">
				</div>
			</div>
            <?php endif; ?>
			<div class="control-group form-group">
				<div class="controls">
					<input type="text" class="mw-ui-field  mw-ui-field-big w100"  name="email" placeholder="<?php _e("Email"); ?>">
				</div>
			</div>
			<div class="control-group form-group">
				<div class="controls">
					<input type="password" class="mw-ui-field  mw-ui-field-big w100" name="password" placeholder="<?php _e("Password"); ?>">
				</div>
			</div>
            <div class="mw-ui-row-nodrop vertical-middle captcha-row">
              <div class="mw-ui-col">
                 <div class="mw-captcha-image-holder"><img class="mw-captcha-img tip" data-tip="<?php _e("Click to refresh"); ?>" data-tipposition="top-center" src="<?php print api_link('captcha') ?>" onclick="mw.tools.refresh_image(this);" /></div>
              </div>
              <div class="mw-ui-col">
                 <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="mw-ui-field" autocomplete="off" name="captcha">
              </div>
              <div class="mw-ui-col">
                <button type="submit" class="mw-ui-btn mw-ui-btn-invert pull-right"><?php print $form_btn_title ?></button>
              </div>
            </div>
            <div class="alert" style="margin: 21px 0 0;display: none;"></div>
			<div style="clear: both"></div>
		</form>
	</div>
</div>
