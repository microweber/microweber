<?php must_have_access(); ?>

<?php
$enable_user_fb_registration = get_option('enable_user_fb_registration', 'users');
$enable_user_google_registration = get_option('enable_user_google_registration', 'users');
$enable_user_github_registration = get_option('enable_user_github_registration', 'users');
$enable_user_twitter_registration = get_option('enable_user_twitter_registration', 'users');
$enable_user_microweber_registration = get_option('enable_user_microweber_registration', 'users');
$enable_user_windows_live_registration = get_option('enable_user_windows_live_registration', 'users');
$enable_user_linkedin_registration = get_option('enable_user_linkedin_registration', 'users');

if ($enable_user_fb_registration == false) {
    $enable_user_fb_registration = 'n';
}

if ($enable_user_google_registration == false) {
    $enable_user_google_registration = 'n';
}

if ($enable_user_github_registration == false) {
    $enable_user_github_registration = 'n';
}

if ($enable_user_twitter_registration == false) {
    $enable_user_twitter_registration = 'n';
}

if ($enable_user_windows_live_registration == false) {
    $enable_user_windows_live_registration = 'n';
}

if ($enable_user_microweber_registration == false) {
    $enable_user_microweber_registration = 'n';
}

if ($enable_user_linkedin_registration == false) {
    $enable_user_linkedin_registration = 'n';
}

$form_show_first_name = get_option('form_show_first_name', 'users');
$form_show_last_name = get_option('form_show_last_name', 'users');
$form_show_address = get_option('form_show_address', 'users');
$form_show_password_confirmation = get_option('form_show_password_confirmation', 'users');
$form_show_newsletter_subscription = get_option('form_show_newsletter_subscription', 'users');

$registration_approval_required = get_option('registration_approval_required', 'users');
if ($registration_approval_required == false) {
    $registration_approval_required = 'n';
}
?>

<script type="text/javascript">
    mw.require('forms.js', true);
    mw.require('options.js', true);
</script>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("User settings updated"); ?>.");
        });
    });

    mw.register_email_send_test = function () {
        var email_to = {}
        email_to.to = $('#test_email_to').val();
        email_to.subject = $('#test_email_subject').val();

        $.post("<?php print site_url('api_html/users/register_email_send_test'); ?>", email_to, function (msg) {
            mw.dialog({
                html: "<pre>" + msg + "</pre>",
                title: "<?php _e('Email send results...'); ?>"
            });
        });
    }

    mw.forgot_password_email_send_test = function () {
        var email_to = {}
        email_to.to = $('#test_email_to').val();
        email_to.subject = $('#test_email_subject').val();

        $.post("<?php print site_url('api_html/users/forgot_password_email_send_test'); ?>", email_to, function (msg) {

            mw.dialog({
                html: "<pre>" + msg + "</pre>",
                title: "<?php _e('Email send results...'); ?>"
            });
        });
    }

    mw.open_captcha_settings = function () {
        mw.dialog({
            content: '<div id="captcha_settings"></div>'
        });

        var params = {}
        mw.load_module('captcha/admin', '#captcha_settings', null, params);
    }
</script>


<div class="<?php print $config['module_class'] ?>">
    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-header px-0">
            <h5><i class="mdi mdi-login text-primary mr-3"></i> <strong><?php _e("Login and register"); ?></strong></h5>
            <div>

            </div>
        </div>

        <div class="card-body pt-3 px-0">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Register options"); ?></h5>
                    <small class="text-muted"><?php _e("Set your settings for proper login and register functionality."); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="control-label"><?php _e("Enable user registration"); ?></label>
                                        <small class="text-muted d-block mb-2"> <?php _e("Do you allow users to register on your website? If you choose \"yes\", they will do that with their email."); ?></small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <?php  $curent_val = get_option('enable_user_registration', 'users'); ?>
                                        <div class="custom-control custom-switch pl-0">
                                            <label class="d-inline-block mr-5" for="enable_user_registration"><?php _e("No"); ?></label>
                                            <input type="checkbox"  data-value-checked="y" data-value-unchecked="n"   class="mw_option_field custom-control-input" name="enable_user_registration" option-group="users" id="enable_user_registration" value="y" <?php if ($curent_val !== 'n'): ?>checked<?php endif; ?>>
                                            <label class="custom-control-label" for="enable_user_registration"><?php _e("Yes"); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="control-label"><?php _e("Registration approval required"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Do you want the user to verify their account after registration? This way you will be sure that the user who has registered is a real person."); ?></small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-switch pl-0">
                                            <label class="d-inline-block mr-5" for="registration_approval_required"><?php _e("No"); ?></label>
                                            <input type="checkbox" class="mw_option_field custom-control-input" name="registration_approval_required" option-group="users" id="registration_approval_required" value="y" <?php if ($registration_approval_required == 'y'): ?>checked<?php endif; ?>>
                                            <label class="custom-control-label" for="registration_approval_required"><?php _e("Yes"); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-body pt-3 px-0">
            <hr class="thin mt-0 mb-5"/>

            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Register form settings"); ?></h5>
                    <small class="text-muted"><?php _e("Customize your registration form by selecting the fields you require and whether you want Captcha certification."); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <a href="#" class="btn btn-link btn-sm py-1 px-0 float-right"><?php _e("View Register Form settings"); ?></a>
                                        <label class="control-label"><?php _e("Set the form fields"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Use the checkbox to determine which visible fields are required for registration."); ?></small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" value="y" <?php if ($form_show_first_name == 'y'): ?> checked <?php endif; ?> name="form_show_first_name" id="form_show_first_name" class="mw_option_field custom-control-input" option-group="users">
                                            <label class="custom-control-label" for="form_show_first_name"><?php _e("Show first name field?"); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" value="y" <?php if ($form_show_last_name == 'y'): ?> checked <?php endif; ?> name="form_show_last_name" id="form_show_last_name" class="mw_option_field custom-control-input" option-group="users">
                                            <label class="custom-control-label" for="form_show_last_name"><?php _e("Show last name field?"); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" value="y" <?php if ($form_show_password_confirmation == 'y'): ?> checked <?php endif; ?> name="form_show_password_confirmation" id="form_show_password_confirmation" class="mw_option_field custom-control-input" option-group="users">
                                            <label class="custom-control-label" for="form_show_password_confirmation"><?php _e("Show password confirmation field?"); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" value="y" <?php if ($form_show_newsletter_subscription == 'y'): ?> checked <?php endif; ?> name="form_show_newsletter_subscription" id="form_show_newsletter_subscription" class="mw_option_field custom-control-input" option-group="users">
                                            <label class="custom-control-label" for="form_show_newsletter_subscription"><?php _e("Show newsletter subscription checkbox?"); ?></label>
                                        </div>
                                    </div>

                                    <hr class="thin"/>

                                    <div class="form-group mb-3">
                                        <a href="javascript:mw.open_captcha_settings();" class="btn btn-link btn-sm py-1 px-0 float-right"><?php _e("View Captcha module settings"); ?></a>
                                        <label class="control-label"><?php _e("Disable Captcha - Registration Form"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Enable or Disable captcha code verification in the registration area."); ?></small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <?php $captcha_disabled = get_option('captcha_disabled', 'users'); ?>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="mw_option_field custom-control-input" option-group="users" name="captcha_disabled" id="captcha_disabled" <?php if ($captcha_disabled == 'y'): ?> checked <?php endif; ?> value="y">
                                            <label class="custom-control-label" for="captcha_disabled"><?php _e('Disable Captcha?'); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="control-label"><?php _e("Disable registration with temporary email?"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Users can register with temporary emails like - Mailinator, MailDrop, Guerrilla... etc"); ?></small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <?php $disable_registration_with_temporary_email = get_option('disable_registration_with_temporary_email', 'users'); ?>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="mw_option_field custom-control-input" option-group="users" name="disable_registration_with_temporary_email" id="disable_registration_with_temporary_email" <?php if ($disable_registration_with_temporary_email == 'y'): ?> checked <?php endif; ?> value="y">
                                            <label class="custom-control-label" for="disable_registration_with_temporary_email"><?php _e('Disable registration with temporary email?'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-body pt-3 px-0">
            <hr class="thin mt-0 mb-5"/>


            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Login form settings"); ?></h5>
                    <small class="text-muted"><?php _e("Customize your registration form by selecting the fields you require and whether you want Captcha certification"); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <a href="javascript:mw.open_captcha_settings();" class="btn btn-link btn-sm py-1 px-0 float-right"><?php _e("View Captcha module settings"); ?></a>
                                        <label class="control-label"><?php _e("Login form settings"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Do I need a captcha foma for login each time?"); ?></small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <?php $login_captcha_enabled = get_option('login_captcha_enabled', 'users'); ?>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="mw_option_field custom-control-input" id="login_captcha_enabled" option-group="users" name="login_captcha_enabled" <?php if ($login_captcha_enabled == 'y'): ?> checked <?php endif; ?> value="y">
                                            <label class="custom-control-label" for="login_captcha_enabled"><?php _e('Require captcha to login?'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-body pt-3 px-0">
            <hr class="thin mt-0 mb-5"/>

            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Social Networks login"); ?></h5>
                    <small class="text-muted"><?php _e("Allow your users to register on your site, blog or store through their social media accounts."); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12 socials-logins-settings">
                                    <div class="form-group mb-3">
                                        <label class="control-label"><?php _e("Enable user registration with socials accounts"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Do you allow users to register on your website with their social media accounts. This will save time of the users to register."); ?></small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <?php $allow_socials_login = get_option('allow_socials_login', 'users'); ?>
                                        <div class="custom-control custom-switch pl-0">
                                            <label class="d-inline-block mr-5" for="users-social-newtworks-login"><?php _e("No"); ?></label>
                                            <input type="checkbox" class="mw_option_field custom-control-input" name="allow_socials_login" id="users-social-newtworks-login" option-group="users" value="y" data-bs-toggle="collapse" data-target="#allow-users-social-newtworks-login" <?php if ($allow_socials_login == 'y'): ?>checked<?php endif; ?> />
                                            <label class="custom-control-label" for="users-social-newtworks-login"><?php _e("Yes"); ?></label>
                                        </div>

                                        <div class="collapse <?php if ($allow_socials_login == 'y'): ?>show<?php endif; ?>" id="allow-users-social-newtworks-login">
                                            <div class="form-group mb-3">
                                                <label class="control-label mb-0"><?php _e("Allow Social Login with"); ?></label>
                                                <hr class="thin"/>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="mw_option_field custom-control-input" option-group="users" id="enable_user_fb_registration" name="enable_user_fb_registration" value="y" <?php if ($enable_user_fb_registration == 'y'): ?> checked <?php endif; ?> data-bs-toggle="collapse" data-target="#fb-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="enable_user_fb_registration"><i class="mdi mdi-facebook mdi-30px lh-1_0 mr-2"></i> <?php _e('Enable login with Facebook?'); ?></label>
                                                </div>
                                            </div>

                                            <div class="collapse <?php if ($enable_user_fb_registration == 'y'): ?>show<?php endif; ?>" id="fb-login-settings">
                                                <small class="d-block mb-1">1. <?php _e("API access"); ?> <a href="https://developers.facebook.com/apps" target="_blank">https://developers.facebook.com/apps</a></small>
                                                <small class="d-block mb-1">2. <?php _e("In Website with Facebook Login please enter"); ?>: <span class="text-muted"><?php print site_url(); ?></span></small>
                                                <small class="d-block mb-1">3. <?php _e("If asked for callback url - use"); ?>: <span class="text-muted"><?php print api_link('social_login_process?provider=facebook') ?></span></small>

                                                <div class="form-group mt-3">
                                                    <label><?php _e("App ID/API Key"); ?></label>
                                                    <input name="fb_app_id" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('fb_app_id', 'users'); ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php _e("App Secret"); ?></label>
                                                    <input name="fb_app_secret" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('fb_app_secret', 'users'); ?>"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_twitter_registration" option-group="users" value="y" <?php if ($enable_user_twitter_registration == 'y'): ?> checked <?php endif; ?> id="enable_user_twitter_registration" data-bs-toggle="collapse" data-target="#twitter-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="enable_user_twitter_registration"><i class="mdi mdi-twitter mdi-30px lh-1_0 mr-2"></i> <?php _e('Twitter login enabled?'); ?></label>
                                                </div>
                                            </div>

                                            <div class="collapse <?php if ($enable_user_twitter_registration == 'y'): ?>show<?php endif; ?>" id="twitter-login-settings">
                                                <small class="d-block mb-1">1. <?php _e("Register your application"); ?> <a href="https://dev.twitter.com/apps" target="_blank">https://dev.twitter.com/apps</a></small>
                                                <small class="d-block mb-1">2. <?php _e("In Website enter"); ?>: <span class="text-muted"><?php print site_url(); ?></span></small>
                                                <small class="d-block mb-1">3. <?php _e("In Callback URL enter"); ?>: <span class="text-muted"><?php print api_link('social_login_process?provider=twitter') ?></span></small>

                                                <div class="form-group mt-3">
                                                    <label><?php _e("Consumer key"); ?></label>
                                                    <input type="text" name="twitter_app_id" class="mw_option_field form-control" option-group="users" value="<?php print get_option('twitter_app_id', 'users'); ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php _e("Consumer secret"); ?></label>
                                                    <input type="text" name="twitter_app_secret" class="mw_option_field form-control" option-group="users" value="<?php print get_option('twitter_app_secret', 'users'); ?>"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="mw_option_field custom-control-input" option-group="users" name="enable_user_github_registration" value="y" <?php if ($enable_user_github_registration == 'y'): ?>checked<?php endif; ?> id="enable_user_github_registration" data-bs-toggle="collapse" data-target="#github-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="enable_user_github_registration"><i class="mdi mdi-github mdi-30px lh-1_0 mr-2"></i> <?php _e('Github login enabled?'); ?></label>
                                                </div>
                                            </div>

                                            <div class="collapse <?php if ($enable_user_github_registration == 'y'): ?>show<?php endif; ?>" id="github-login-settings">
                                                <small class="d-block mb-1">1. <?php _e("Register your application"); ?> <a href="https://github.com/settings/applications/new" target="_blank">https://github.com/settings/applications/new</a></small>
                                                <small class="d-block mb-1">2. <?php _e("In Main URL enter"); ?>: <span class="text-muted"><?php print site_url(); ?></span></small>
                                                <small class="d-block mb-1">3. <?php _e("In Callback URL enter"); ?>: <span class="text-muted"><?php print api_link('social_login_process?provider=github') ?></span></small>

                                                <div class="form-group mt-3">
                                                    <label><?php _e("Client ID"); ?></label>
                                                    <input name="github_app_id" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('github_app_id', 'users'); ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php _e("Client secret"); ?></label>
                                                    <input name="github_app_secret" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('github_app_secret', 'users'); ?>"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_linkedin_registration" option-group="users" id="enable_user_linkedin_registration" value="y" <?php if ($enable_user_linkedin_registration == 'y'): ?> checked <?php endif; ?> data-bs-toggle="collapse" data-target="#linkedin-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="enable_user_linkedin_registration"><i class="mdi mdi-linkedin mdi-30px lh-1_0 mr-2"></i> <?php _e('Linked In login enabled?'); ?></label>
                                                </div>
                                            </div>

                                            <div class="collapse <?php if ($enable_user_linkedin_registration == 'y'): ?>show<?php endif; ?>" id="linkedin-login-settings">
                                                <small class="d-block mb-1">1. <?php _e("Register your application"); ?> <a href="https://www.linkedin.com/secure/developer" target="_blank">https://www.linkedin.com/secure/developer</a></small>
                                                <small class="d-block mb-1">2. <?php _e("In Website enter"); ?>: <span class="text-muted"><?php print site_url(); ?></span></small>
                                                <small class="d-block mb-1">3. <?php _e("In Callback URL enter"); ?>: <span class="text-muted"><?php print api_link('social_login_process?provider=linkedin') ?></span></small>

                                                <div class="form-group mt-3">
                                                    <label><?php _e("Client ID"); ?></label>
                                                    <input type="text" name="linkedin_app_id" class="mw_option_field form-control" option-group="users" value="<?php print get_option('linkedin_app_id', 'users'); ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php _e("Client Secret"); ?></label>
                                                    <input type="text" name="linkedin_app_secret" class="mw_option_field form-control" option-group="users" value="<?php print get_option('linkedin_app_secret', 'users'); ?>"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_google_registration" option-group="users" id="enable_user_google_registration" value="y" <?php if ($enable_user_google_registration == 'y'): ?> checked <?php endif; ?> data-bs-toggle="collapse" data-target="#google-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="enable_user_google_registration"><i class="mdi mdi-google mdi-30px lh-1_0 mr-2"></i> <?php _e('Google login enabled?'); ?></label>
                                                </div>
                                            </div>

                                            <div class="collapse <?php if ($enable_user_google_registration == 'y'): ?>show<?php endif; ?>" id="google-login-settings">
                                                <small class="d-block mb-1">1. <?php _e("Set your API access"); ?> <a href="https://code.google.com/apis/console/" target="_blank">https://code.google.com/apis/console/</a></small>
                                                <small class="d-block mb-1">2. <?php _e("In redirect URI please enter"); ?>: <span class="text-muted"><?php print api_link('social_login_process?provider=google') ?></span></small>

                                                <div class="form-group mt-3">
                                                    <label><?php _e("Client ID"); ?></label>
                                                    <input type="text" name="google_app_id" class="mw_option_field form-control" option-group="users" value="<?php print get_option('google_app_id', 'users'); ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php _e("Client secret"); ?></label>
                                                    <input name="google_app_secret" class="mw_option_field form-control" style="" type="text" option-group="users" value="<?php print get_option('google_app_secret', 'users'); ?>"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_microweber_registration" option-group="users" id="enable_user_microweber_registration" value="y" <?php if ($enable_user_microweber_registration == 'y'): ?>checked<?php endif; ?> data-bs-toggle="collapse" data-target="#mw-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="enable_user_microweber_registration"><i class="mdi mdi-microweber mdi-30px lh-1_0 mr-2"></i> <?php _e('Microweber login enabled?'); ?></label>
                                                </div>
                                            </div>

                                            <div class="collapse <?php if ($enable_user_microweber_registration == 'y'): ?>show<?php endif; ?>" id="mw-login-settings">
                                                <small class="d-block mb-1"><?php _e("Please enter your credentials for Microweber Login Server"); ?></small>
                                                <?php
                                                $microweber_app_url = get_option('microweber_app_url', 'users');
                                                if (empty($microweber_app_url)) {
                                                    $microweber_app_url = 'https://mwlogin.com';
                                                }
                                                ?>

                                                <div class="form-group mt-3">
                                                    <label><?php _e("Server URL"); ?></label>
                                                    <input name="microweber_app_url" class="mw_option_field form-control" type="text" option-group="users" value="<?php print $microweber_app_url; ?>"/>
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label><?php _e("Client ID"); ?></label>
                                                    <input name="microweber_app_id" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('microweber_app_id', 'users'); ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php _e("Client secret"); ?></label>
                                                    <input name="microweber_app_secret" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('microweber_app_secret', 'users'); ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-none style-1 mb-0 card-settings" id="email-notifications">
        <div class="card-body pt-3 px-0">
            <hr class="thin mt-0 mb-5"/>

            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Email notifications"); ?></h5>
                    <small class="text-muted"><?php _e("Register users can automatically receive an automatic email from you. See the settings and post your messages."); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <div class="form-group mb-3">
                                            <label class="control-label"><?php _e("Send email on new user registration"); ?></label>
                                            <small class="text-muted d-block mb-2"><?php _e("Do you want users to receive an e-mail when registering?"); ?></small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="register_email_enabled"><?php _e("No"); ?></label>
                                                <input name="register_email_enabled" id="register_email_enabled" class="mw_option_field custom-control-input" data-option-group="users" value="1" type="checkbox" <?php if (get_option('register_email_enabled', 'users') == 1): ?>checked<?php endif; ?>>
                                                <label class="custom-control-label" for="register_email_enabled"><?php _e("Yes"); ?></label>
                                            </div>
                                        </div>



                                        <div class="form-group mb-3">
                                            <label class="control-label"><?php _e("Send email on new user registration to admin users"); ?></label>
                                            <small class="text-muted d-block mb-2"><?php _e("Do you want adminitrators to receive an e-mail when new user is registered?"); ?></small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="register_email_to_admins_enabled"><?php _e("No"); ?></label>
                                                <input name="register_email_to_admins_enabled" id="register_email_to_admins_enabled" class="mw_option_field custom-control-input" data-option-group="users" value="1" type="checkbox" <?php if (get_option('register_email_to_admins_enabled', 'users') == 1): ?>checked<?php endif; ?>>
                                                <label class="custom-control-label" for="register_email_to_admins_enabled"><?php _e("Yes"); ?></label>
                                            </div>
                                        </div>



                                        <div class="form-group mb-3">
                                            <label class="control-label"><?php _e("Require e-mail verification on new user registration"); ?></label>
                                            <small class="text-muted d-block mb-2"><?php _e("Do you want users to verify their e-mail address when registering?"); ?></small>
                                        </div>

                                        <div class="form-group mb-4">
	                                        <div class="custom-control custom-switch pl-0">
	                                            <label class="d-inline-block mr-5" for="register_email_verify"><?php _e("No"); ?></label>
		                                        <input name="register_email_verify" id="register_email_verify" class="mw_option_field custom-control-input" data-option-group="users" value="y" type="checkbox" <?php if (get_option('register_email_verify', 'users') == 'y'): ?> checked <?php endif; ?>>
		                                        <label class="custom-control-label" for="register_email_verify"><?php _e("Yes"); ?></label>
		                                    </div>
                                        </div>

                                        <module type="admin/mail_templates/select_template" option_group="users" mail_template_type="new_user_registration" class="mb-4"/>

                                        <a onclick="mw.register_email_send_test();" href="javascript:;" class="btn btn-outline-primary btn-sm"><?php _e('Send Test Email'); ?></a>
                                    </div>

                                    <div>
                                        <div class="form-group mb-3">
                                            <label class="control-label"><?php _e("Send custom forgot password email"); ?></label>
                                            <small class="text-muted d-block mb-2"><?php _e("Select which template the users will receive when try to reset their password?"); ?></small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="forgot_pass_email_enabled"><?php _e("No"); ?></label>
                                                <input name="forgot_pass_email_enabled" id="forgot_pass_email_enabled" class="mw_option_field custom-control-input" data-option-group="users" value="1" type="checkbox" <?php if (get_option('forgot_pass_email_enabled', 'users') == 1): ?>checked<?php endif; ?>>
                                                <label class="custom-control-label" for="forgot_pass_email_enabled"><?php _e("Yes"); ?></label>
                                            </div>
                                        </div>

                                        <module type="admin/mail_templates/select_template" option_group="users" mail_template_type="forgot_password" class="mb-4"/>

                                        <a onclick="mw.forgot_password_email_send_test();" href="javascript:;" class="btn btn-outline-primary btn-sm"><?php _e('Send Test Email'); ?></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-body pt-3 px-0">
            <hr class="thin mt-0 mb-5"/>

            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Other settings"); ?></h5>
                    <small class="text-muted"><?php _e("Advanced setting where you can set different URL addresses."); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Register URL"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("You can set a custom url for the register page"); ?></small>
                                        <input name="register_url" class="mw_option_field form-control type=" text" option-group="users" value="<?php print get_option('register_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Login URL"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("You can set a custom url for the login page"); ?></small>
                                        <input name="login_url" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('login_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Logout URL"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("You can set a custom url for the logout page"); ?></small>
                                        <input name="logout_url" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('logout_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Forgot password URL"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("You can set a custom url for the forgot password page"); ?></small>
                                        <input name="forgot_password_url" class="mw_option_field form-control" type="text" option-group="users" value="<?php print get_option('forgot_password_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
