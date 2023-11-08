<?php

/*

type: layout

name: Default

description: Default register template

*/

?>
<?php if (is_logged() == false): ?>
    <script type="text/javascript">
        mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css");
        mw.require('forms.js', true);
        mw.require('url.js', true);
        $(document).ready(function () {
            mw.$('#user_registration_form_holder').submit(function () {
                mw.form.post(mw.$('#user_registration_form_holder'), '<?php print site_url('api') ?>/user_register', function () {
                    mw.response('#register_form_holder', this);
                    if (this.success) {
                        mw.reload_module('users/register');
                        window.location.href = window.location.href;
                    }
                });
                return false;
            });
        });
    </script>

    <div id="register_form_holder">
        <h4  class="text-center pt-3">
            <?php _e('Register new account.'); ?>
        </h4>
        <p class="text-center pb-4"><?php _e('We are glad to welcome you in our community.'); ?></p>
        <form class="p-t-10" action="#" id="user_registration_form_holder" method="post">
            <?php print csrf_form(); ?>
            <?php if ($form_show_first_name): ?>
                <div class="form-group">
                    <label class="form-label"><?php _e('First Name'); ?></label>
                    <input class="form-control input-lg" type="text" name="first_name" placeholder="<?php _e('First Name'); ?>">
                </div>
            <?php endif; ?>

            <?php if ($form_show_last_name): ?>
                <div class="form-group">
                    <label class="form-label"><?php _e('Last Name'); ?></label>
                    <input class="form-control input-lg" type="text" name="last_name" placeholder="<?php _e('Last Name'); ?>">
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label"><?php _e('E-mail'); ?></label>
                <input class="form-control input-lg" type="email" name="email" placeholder="<?php _e('E-mail'); ?>">
            </div>

            <div class="form-group">
                <label class="form-label"><?php _e('Password'); ?></label>
                <input class="form-control input-lg" type="password" name="password" placeholder="<?php _e('Password'); ?>">
            </div>

            <?php if ($form_show_password_confirmation): ?>
                <div class="form-group">
                    <label class="form-label"><?php _e('Confirm Password'); ?></label>
                    <input class="form-control input-lg" type="password" name="password2" placeholder="<?php _e("Confirm Password"); ?>">
                </div>
            <?php endif; ?>

            <?php if (!$captcha_disabled): ?>
                <label class="form-label"><?php _e('Security code'); ?></label>
                <module type="captcha"/>
            <?php endif; ?>


            <div class="row">
                <div class="col-12">
                    <p class="personal-data"><?php _e("Your personal data will be used to support your expirience
                        throughout this website, to manage access to your account
                        and for other purposes described in our"); ?><?php _e("privacy policy"); ?>.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block my-3 text-center justify-content-center"><?php print $form_btn_title ?></button>
        </form>
    </div>
<?php else: ?>
    <p class="text-center">
        <?php _e("You Are Logged In"); ?>
    </p>
<?php endif; ?>
<br/>
