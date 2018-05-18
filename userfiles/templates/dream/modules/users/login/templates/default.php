<?php

/*

type: layout

name: Default

description: Login default

*/

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>

<?php if ($user != false): ?>
    <div class="box-static box-border-top padding-30">
        <module type="users/profile"/>
    </div>
<?php else: ?>
    <div class="" id="user_login_holder_<?php print $params['id'] ?>">


        <div class="alert alert-mini alert-danger margin-bottom-30" style="margin: 0;display: none;"></div>

        <form method="post" id="user_login_<?php print $params['id'] ?>" action="#" autocomplete="off">
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-with-icon">
                        <label><?php _e("Email or username"); ?>:</label>
                        <i class="icon icon-Male-2"></i>
                        <input type="text" name="username" placeholder="<?php _e("Email or username"); ?>"/>
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

            <?php if (isset($login_captcha_enabled) and $login_captcha_enabled): ?>
                <module type="captcha"/>
            <?php endif; ?>

            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn--primary"><?php _e("Login"); ?></button>
                </div>
            </div>
            <div class="row text-center">
                <a href="<?php print register_url(); ?>"><?php _lang("Create an account", "templates/dream"); ?></a>
            </div>
            <div class="row text-center">
                <p class="type--fine-print">
                    <?php _lang("Forgot Password ?", "templates/dream"); ?>
                    <a href="<?php print forgot_password_url(); ?>"><?php _lang("Start password recovery", "templates/dream"); ?></a>
                </p>
            </div>
        </form>

        <hr/>

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

        <?php if ($have_social_login): ?>


            <div class="text-center">
                <div class="margin-bottom-20">&ndash; <?php _e("OR"); ?> &ndash;</div>

                <?php if ($facebook): ?>
                    <a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="btn btn--primary m-t-10">
                        <span class="btn__text">
                        <i class="fa fa-facebook"></i> Sign in with Facebook</span>
                    </a>
                    <br/>
                <?php endif; ?>

                <?php if ($twitter): ?>
                    <a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="btn btn--primary m-t-10">
                        <span class="btn__text"><i class="fa fa-twitter"></i> Sign in with Twitter</span>
                    </a>
                    <br/>
                <?php endif; ?>

                <?php if ($google): ?>
                    <a href="<?php print api_link('user_social_login?provider=google') ?>" class="btn btn--primary m-t-10">
                        <span class="btn__text"><i class="fa fa-google"></i> Sign in with Google+</span>
                    </a>
                    <br/>
                <?php endif; ?>

                <?php if ($github): ?>
                    <a href="<?php print api_link('user_social_login?provider=github') ?>" class="btn btn--primary m-t-10">
                        <span class="btn__text"><i class="fa fa-github"></i> Sign in with Github</span>
                    </a>
                    <br/>
                <?php endif; ?>

                <?php if ($mw_login): ?>
                    <a href="<?php print api_link('user_social_login?provider=github') ?>" class="btn btn--primary m-t-10">
                        <span class="btn__text"><i class="mw-icon-mw"></i> Sign in with Microweber</span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>

