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
            mw.$('#user_registration_form_holder_checkout_v2').submit(function () {
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('api.user.register'); ?>",
                    data: $('#user_registration_form_holder_checkout_v2').serialize()
                }).done(function(data){
                    if (data.success) {
                        window.location.href = "<?php echo route('checkout.contact_information'); ?>";
                    }
                });
                return false;
            });
        });
    </script>

    <div class="mt-5 col-12" id="register_form_holder">
        <div class="d-flex pb-4">
            <h4><?php _e("Registration"); ?></h4>
            <a class="ml-auto align-self-center" href="<?php print route('checkout.login'); ?>">
                <?php _e("Already have account?"); ?>
            </a>
        </div>
        <br />

        <form class="p-t-10" action="#" id="user_registration_form_holder_checkout_v2" method="post">
            <?php print csrf_form(); ?>
            <?php if ($form_show_first_name): ?>
                <div class="form-group">
                    <label class="control-label"><?php _e("Firs name"); ?></label>
                    <input class="form-control input-lg" type="text" name="first_name">
                </div>
            <?php endif; ?>

            <?php if ($form_show_last_name): ?>
                <div class="form-group">
                    <label class="control-label"><?php _e("Last name"); ?></label>
                    <input class="form-control input-lg" type="text" name="last_name">
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="control-label"><?php _e("Email"); ?></label>
                <input class="form-control input-lg" type="email" name="email">
            </div>

            <div class="form-group m-t-20">
                <label class="control-label"><?php _e("Password"); ?></label>
                <input class="form-control input-lg" type="password" name="password">
            </div>

            <?php if ($form_show_password_confirmation): ?>
                <div class="form-group m-t-20">
                    <label class="control-label"><?php _e("Confirm password"); ?></label>
                    <input class="form-control input-lg" type="password" name="password2">
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <small class="personal-data text-muted"><?php _e("Your personal data will be used to support your experience
                        throughout this website, to manage access to your account
                        and for other purposes described in our"); ?> <a href="#"><?php _e("privacy policy"); ?></a>.</small>
                </div>
            </div>

            <div class="d-flex align-items-center mt-3">
                <?php if (!$captcha_disabled): ?>
                    <module type="captcha" template="checkout_v2"/>
                <?php endif; ?>
                <button type="submit" class="btn btn-outline-primary ml-auto px-5"><?php print $form_btn_title ?></button>
            </div>

            <input type="hidden" name="login" value="<?php echo route('checkout.contact_information'); ?>"/>

        </form>
    </div>
<?php else: ?>
    <p class="text-center">
        You Are Logged In
    </p>
<?php endif; ?>
<br/>
