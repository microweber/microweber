<?php

/*

type: layout

name: Checkout default

description: Checkout default

*/

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>
<script>mw.moduleCSS("<?php print modules_url(); ?>users/login/templates.css")</script>


<script type="text/javascript">

    mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css");
    mw.require('forms.js', true);
    mw.require('url.js', true);

    $(document).ready(function () {
        mw.$('#user_login_checkout_v2').submit(function () {
            $.ajax({
                type: "POST",
                url: "<?php echo route('api.user.login'); ?>",
                data: $('#user_login_checkout_v2').serialize()
            }).done(function(data){
                if (data.success) {
                    window.location.href = "<?php echo route('checkout.contact_information'); ?>";
                }
            });
            return false;
        });
    });
</script>

<div id="mw-login" class="module-login mt-5 col-12">
    <?php if ($user != false): ?>
        <div>
            <module type="users/profile"/>
        </div>
    <?php else: ?>
        <div id="user_login_holder_checkout_v2">

            <div class="d-flex pb-4">
                <h4><?php _e("Login"); ?></h4>
                <a class="ml-auto align-self-center" href="<?php print route('checkout.register'); ?>">
                    <?php _e("New registration"); ?>
                </a>
            </div>
            <br />

            <form method="post" id="user_login_checkout_v2" class="clearfix">
                <div class="control-group form-group">
                    <label class="control-label"><?php _e("Email or username"); ?></label>
                    <input class="large-field form-control" name="username" <?php if (isset($input['username']) != false): ?> value="<?php print $input['username'] ?>"  <?php endif;  ?> type="text" />
                </div>
                <div class="control-group form-group">
                    <label class="control-label"><?php _e("Password"); ?></label>
                    <input class="large-field form-control" name="password" <?php if (isset($input['password']) != false): ?> value="<?php print $input['password'] ?>"  <?php endif;  ?> type="password" />
                </div>
                <?php if (isset($login_captcha_enabled) and $login_captcha_enabled): ?>
                    <module type="captcha" template="admin" />
                <?php endif; ?>
                <div class="d-flex align-items-center">
                    <a class="reset-password-link" href="<?php echo route('checkout.forgot_password'); ?>">
                        <?php _e("Forgot password"); ?>
                        ?</a>
                    <button class="btn btn-outline-primary ml-auto px-5" type="submit"> <?php _e("Login"); ?></button>
                </div>


                <div class="alert" style="margin: 0;display: none;"></div>
                <div class="social-login">
                    <?php
                    # Login Providers
                    $facebook = get_option('enable_user_fb_registration', 'users') == 'y';
                    $twitter = get_option('enable_user_twitter_registration', 'users') == 'y';
                    $google = get_option('enable_user_google_registration', 'users') == 'y';
                    $windows = get_option('enable_user_windows_live_registration', 'users') == 'y';
                    $github = get_option('enable_user_github_registration', 'users') == 'y';
                    $mw_login = get_option('enable_user_microweber_registration', 'users') == 'y';

                    if ($facebook or $twitter or $google or $windows or $github) {
                        $have_social_login = true;
                    } else {
                        $have_social_login = false;
                    }
                    ?>
                    <?php if ($have_social_login) { ?>
                        <h5>
                            <?php _e("Login with"); ?>
                            :</h5>
                    <?php } ?>
                    <?php if ($have_social_login){ ?>
                    <ul>
                        <?php } ?>
                        <?php if ($facebook): ?>
                            <li><a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="mw-signin-with-facebook">Facebook login</a></li>
                        <?php endif; ?>
                        <?php if ($twitter): ?>
                            <li><a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="mw-signin-with-twitter">Twitter login</a></li>
                        <?php endif; ?>
                        <?php if ($google): ?>
                            <li><a href="<?php print api_link('user_social_login?provider=google') ?>" class="mw-signin-with-google">Google login</a></li>
                        <?php endif; ?>
                        <?php if ($github): ?>
                            <li><a href="<?php print api_link('user_social_login?provider=github') ?>" class="mw-signin-with-github">Github login</a></li>
                        <?php endif; ?>
                        <?php if ($mw_login): ?>
                            <li><a href="<?php print api_link('user_social_login?provider=microweber') ?>" class="mw-signin-with-microweber">Microweber login</a></li>
                        <?php endif; ?>
                        <?php if ($have_social_login){ ?>
                    </ul>
                <?php } ?>
                </div>

                <?php if (isset($_GET['redirect'])): ?>
                <input type="hidden" value="<?php echo mw()->format->clean_xss($_GET['redirect']); ?>" name="redirect">
                <?php endif; ?>

            </form>
        </div>

          <?php
          if(get_option('register_email_verify', 'users') == 'y'){
              if(isset($_GET['verify_email'])) {
          ?>
          <div class="pull-left alert alert-warning"><?php _e("Please check your inbox for your account activation email"); ?></div>
          <?php
              } elseif(isset($_GET['email_verified'])) {
          ?>
          <div class="pull-left alert alert-success"><?php _e("Success! Your email has been verified and account activated. You can now login."); ?></div>
          <?php
              }
          }
          ?>

    <?php endif; ?>
</div>
