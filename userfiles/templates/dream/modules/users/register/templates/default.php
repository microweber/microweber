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

    <div id="register_form_holder">

        <form class="nomargin sky-form" action="#" id="user_registration_form_holder" method="post">
            <?php print csrf_form(); ?>
            <?php if ($form_show_first_name): ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-with-icon">
                            <label><?php _e('First Name'); ?>:</label>
                            <i class="icon icon-Male-2"></i>
                            <input type="text" name="first_name" placeholder="<?php _e('First Name'); ?>"/>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($form_show_last_name): ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-with-icon">
                            <label><?php _e("Last Name"); ?>:</label>
                            <i class="icon icon-Male-2"></i>
                            <input type="text" name="last_name" placeholder="<?php _e("Last Name"); ?>"/>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="input-with-icon">
                        <label><?php _e("Email address"); ?>:</label>
                        <i class="icon icon-Mail-2"></i>
                        <input type="email" name="email" placeholder="you@yourwebsite.com"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="input-with-icon">
                        <label><?php _e("Password"); ?>:</label>
                        <i class="icon icon-Security-Check"></i>
                        <input type="password" name="password" placeholder="&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;"/>
                    </div>
                </div>
            </div>

            <?php if ($form_show_password_confirmation): ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-with-icon">
                            <label><?php _e("Confirm Password"); ?>:</label>
                            <i class="icon icon-Security-Check"></i>
                            <input type="password" name="password2" placeholder="&bullet;&bullet;&bullet;&bullet;&bullet;&bullet;"/>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$captcha_disabled): ?>
                <module type="captcha"/>
            <?php endif; ?>

            <hr/>

            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn--primary"><?php print $form_btn_title ?></button>
                </div>
            </div>
        </form>

        <div class="row text-center">
            <a href="<?php print login_url(); ?>"><?php _lang("You already have an account ?", "templates/dream"); ?></a>
        </div>
    </div>
<?php else: ?>
    You Are Logged In
<?php endif; ?>