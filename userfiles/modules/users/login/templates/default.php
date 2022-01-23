<?php

/*

type: layout

name: Login default

description: Login default

*/

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>
<script>mw.moduleCSS("<?php print modules_url(); ?>users/login/templates.css")</script>

<div id="mw-login" class="module-login well">
    <?php if ($user != false): ?>
        <div>
            <module type="users/profile"/>
        </div>
    <?php else: ?>
        <div class="box-head">
            <h2>
                <?php _e("Login or"); ?>
                <a href="<?php print register_url(); ?>">
                    <?php _e("Register"); ?>
                </a></h2>
        </div>
        <div id="user_login_holder_<?php print $params['id'] ?>">
            <form method="post" id="user_login_<?php print $params['id'] ?>" class="clearfix" action="#">
                <div class="control-group form-group">
                    <input class="large-field form-control" name="username" <?php if (isset($input['username']) != false): ?> value="<?php print $input['username'] ?>"  <?php endif;  ?> type="text" placeholder="<?php _e("Email or username"); ?>"/>
                </div>
                <div class="control-group form-group">
                    <input class="large-field form-control" name="password" <?php if (isset($input['password']) != false): ?> value="<?php print $input['password'] ?>"  <?php endif;  ?> type="password" placeholder="<?php _e("Password"); ?>"/>
                </div>
                <?php if (isset($login_captcha_enabled) and $login_captcha_enabled): ?>
                    <module type="captcha" template="admin" />
                <?php endif; ?>
                <a class="reset-password-link" href="<?php print forgot_password_url(); ?>">
                    <?php _e("Forgot password"); ?>
                    ?</a>
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
                <button type="submit" class="btn btn-outline-dark btn-lg btn-block my-3 text-center justify-content-center"><?php print $form_btn_title ?></button>

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
