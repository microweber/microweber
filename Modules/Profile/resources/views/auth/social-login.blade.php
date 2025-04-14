<div class="social-login">
    <?php
    # Login Providers
    $facebook = get_option('enable_user_fb_registration', 'users');
    $twitter = get_option('enable_user_twitter_registration', 'users');
    $google = get_option('enable_user_google_registration', 'users');
    $windows = get_option('enable_user_windows_live_registration', 'users');
    $github = get_option('enable_user_github_registration', 'users');
    $mw_login = get_option('enable_user_microweber_registration', 'users');

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
        <li><a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="mw-signin-with-facebook">
                Facebook login</a></li>
        <?php endif; ?>
        <?php if ($twitter): ?>
        <li><a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="mw-signin-with-twitter">Twitter
                login</a></li>
        <?php endif; ?>
        <?php if ($google): ?>
        <li><a href="<?php print api_link('user_social_login?provider=google') ?>" class="mw-signin-with-google">Google
                login</a></li>
        <?php endif; ?>
        <?php if ($github): ?>
        <li><a href="<?php print api_link('user_social_login?provider=github') ?>" class="mw-signin-with-github">Github
                login</a></li>
        <?php endif; ?>
        <?php if ($mw_login): ?>
        <li><a href="<?php print api_link('user_social_login?provider=microweber') ?>"
               class="mw-signin-with-microweber">Microweber login</a></li>
        <?php endif; ?>
        <?php if ($have_social_login){ ?>
    </ul>
    <?php } ?>
</div>
