<?php

/*

type: layout

name: Default

description: Default register template

*/

?>
<script type="text/javascript">
    mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css");
    mw.require('forms.js', true);
    mw.require('url.js', true);
    $(document).ready(function () {
        mw.$('#user_registration_form_holder').submit(function () {
            mw.form.post(mw.$('#user_registration_form_holder'), '<?php print site_url('api') ?>/user_register', function () {
                mw.response('#register_form_holder', this);
                if (typeof this.success !== 'undefined') {
                    mw.form.post(mw.$('#user_registration_form_holder'), '<?php print site_url('api') ?>/user_login', function () {
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
        <form id="user_registration_form_holder" method="post" class="reg-form-clearfix">

            <?php print csrf_form(); ?>

            <?php if ($form_show_first_name): ?>
                <div class="control-group form-group">
                    <div class="controls">
                        <input type="text" class="large-field form-control" name="first_name" placeholder="<?php _e("First name"); ?>" required>
                    </div>
                </div>

            <?php endif; ?>

            <?php if ($form_show_last_name): ?>
                <div class="control-group form-group">
                    <div class="controls">
                        <input type="text" class="large-field form-control" name="last_name" placeholder="<?php _e("Last name"); ?>" required>
                    </div>
                </div>

            <?php endif; ?>
            <div class="control-group form-group">
                <div class="controls">
                    <input type="text" class="large-field form-control" name="email" placeholder="<?php _e("Email"); ?>" required>
                </div>
            </div>

            <div class="control-group form-group">
                <div class="controls">
                    <input type="password" class="large-field form-control" name="password" placeholder="<?php _e("Password"); ?>" required>
                </div>
            </div>

            <?php if ($form_show_password_confirmation): ?>
                <div class="control-group form-group">
                    <div class="controls">
                        <input type="password" class="large-field form-control" name="password2" placeholder="<?php _e("Confirm password"); ?>" required>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($show_newsletter_subscription == 'y' && !$newsletter_subscribed): ?>
			<div class="control-group form-group">
				<div class="custom-control custom-checkbox">
					<label class="mw-ui-check">
						<input type="checkbox" name="newsletter_subscribe" value="1" autocomplete="off"/> <span></span>
						<span><?php _e("Please email me your monthly news and special offers"); ?></span>
					</label>
				</div>
			</div>
            <?php endif; ?>

		    <?php if($require_terms): ?>
                <module type="users/terms" data-for="registration" />
            <?php endif; ?>

			<?php if (get_option('disable_captcha', $params['id']) != 'y'): ?>
            <div class="mw-ui-row vertical-middle captcha-row">
                <div class="mw-ui-col">
                    <module type="captcha"/>
                </div>
            </div>
			<?php endif; ?>

            <div class="alert" style="margin: 0;display: none;"></div>

            <button type="submit" class="btn btn-default pull-right"><?php print $form_btn_title ?></button>

            <div style="clear: both"></div>
        </form>

    </div>
</div>
