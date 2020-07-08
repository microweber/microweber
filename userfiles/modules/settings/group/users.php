<?php only_admin_access(); ?>

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

            mw.tools.modal.init({

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

            mw.tools.modal.init({
                html: "<pre>" + msg + "</pre>",
                title: "<?php _e('Email send results...'); ?>"
            });
        });
    }

</script>


<div class="<?php print $config['module_class'] ?>">
    <div class="card bg-none style-1 mb-0">
        <div class="card-header">
            <h5><i class="mdi mdi-login text-primary mr-3"></i> <strong><?php _e("Login and register"); ?></strong></h5>
            <div>

            </div>
        </div>

        <div class="card-body pt-3">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="font-weight-bold"><?php _e("Register options"); ?></h5>
                    <small class="text-muted">Set your settings for proper login and register functionality.</small>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="control-label"><?php _e("Enable user registration"); ?></label>
                                        <small class="text-muted d-block mb-2">Do you allow users to register on your website? If you choose “yes”, they will do that with their email.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <?php $curent_val = get_option('enable_user_registration', 'users'); ?>
                                        <div class="custom-control custom-switch pl-0">
                                            <label class="d-inline-block mr-5" for="enable_user_registration">No</label>
                                            <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_registration" option-group="users" id="enable_user_registration" value="y" <?php if ($curent_val == 'y'): ?>checked<?php endif; ?>>
                                            <label class="custom-control-label" for="enable_user_registration">Yes</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="control-label"><?php _e("Registration approval required"); ?></label>
                                        <small class="text-muted d-block mb-2">Do you want the user to verify their account after registration? This way you will be sure that the user who has registered is a real person.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-switch pl-0">
                                            <label class="d-inline-block mr-5" for="registration_approval_required">No</label>
                                            <input type="checkbox" class="mw_option_field custom-control-input" name="registration_approval_required" option-group="users" id="registration_approval_required" value="y" <?php if ($registration_approval_required == 'y'): ?>checked<?php endif; ?>>
                                            <label class="custom-control-label" for="registration_approval_required">Yes</label>
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

    <div class="card bg-none style-1 mb-0">
        <div class="card-body pt-3">
            <hr class="thin mt-0 mb-5"/>

            <div class="row">
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Register form settings</h5>
                    <small class="text-muted">Customize your registration form in the best way by specifying the fields you want are required and you want Captcha certification.</small>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <a href="#" class="btn btn-link btn-sm py-1 px-0 float-right">View Register Form settings</a>
                                        <label class="control-label">Set the form fields</label>
                                        <small class="text-muted d-block mb-2">Use the checkbox to determine which visible fields are required for registration.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                            <label class="custom-control-label" for="customCheck1">Show first name field?</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                            <label class="custom-control-label" for="customCheck1">Show first name field?</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                            <label class="custom-control-label" for="customCheck1">Show password confirmation field?</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                            <label class="custom-control-label" for="customCheck1">Show newsletter subscription field?</label>
                                        </div>
                                    </div>

                                    <hr class="thin"/>

                                    <div class="form-group mb-3">
                                        <a href="#" class="btn btn-link btn-sm py-1 px-0 float-right">View Captcha module settings</a>
                                        <label class="control-label">Disable Captcha - Registration Form</label>
                                        <small class="text-muted d-block mb-2">Enable or Disable captcha code verification in the registration area.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                            <label class="custom-control-label" for="customCheck1">Disable Captcha?</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="control-label">Disable registration with temporary email?</label>
                                        <small class="text-muted d-block mb-2">Usars can register with temporary emails like - Mailinator, MailDrop, Guerrilla...</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                            <label class="custom-control-label" for="customCheck1">Disable registration with temporary email?</label>
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

    <div class="card bg-none style-1 mb-0">
        <div class="card-body pt-3">
            <hr class="thin mt-0 mb-5"/>

            <div class="row">
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Login form settings</h5>
                    <small class="text-muted">Customize your registration form in the best way by specifying the fields you want are required and you want Captcha certification</small>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <a href="#" class="btn btn-link btn-sm py-1 px-0 float-right">View Captcha module settings</a>
                                        <label class="control-label">Login form settings</label>
                                        <small class="text-muted d-block mb-2">Do I need a captcha foma for login each time?</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                            <label class="custom-control-label" for="customCheck1">Require Captcha to Login?</label>
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

    <div class="card bg-none style-1 mb-0">
        <div class="card-body pt-3">
            <hr class="thin mt-0 mb-5"/>

            <div class="row">
                <div class="col-md-4">
                    <h5 class="font-weight-bold">Social Networks login</h5>
                    <small class="text-muted">Allow your users to register on your site, blog or store through their social media accounts.</small>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12 socials-logins-settings">
                                    <div class="form-group mb-3">
                                        <label class="control-label"><?php _e("Enable user registration with socials accounts"); ?></label>
                                        <small class="text-muted d-block mb-2">Do you allow users to register on your website with their social media accounts. This will save time of the users to register.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <?php $allow_socials_login = get_option('allow_socials_login', 'users'); ?>
                                        <div class="custom-control custom-switch pl-0">
                                            <label class="d-inline-block mr-5" for="users-social-newtworks-login">No</label>
                                            <input type="checkbox" class="mw_option_field custom-control-input" name="allow_socials_login" id="users-social-newtworks-login" option-group="users" value="y" data-toggle="collapse" data-target="#allow-users-social-newtworks-login" <?php if ($allow_socials_login == 'y'): ?>checked<?php endif; ?> />
                                            <label class="custom-control-label" for="users-social-newtworks-login">Yes</label>
                                        </div>

                                        <div class="collapse <?php if ($allow_socials_login == 'y'): ?>show<?php endif; ?>" id="allow-users-social-newtworks-login">
                                            <div class="form-group mb-3">
                                                <label class="control-label mb-0"><?php _e("Allow Social Login with"); ?></label>
                                                <hr class="thin"/>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="mw_option_field custom-control-input" option-group="users" id="enable_user_fb_registration" name="enable_user_fb_registration" value="y" <?php if ($enable_user_fb_registration == 'y'): ?> checked <?php endif; ?> data-toggle="collapse" data-target="#fb-login-settings">
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
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_twitter_registration" option-group="users" value="y" <?php if ($enable_user_twitter_registration == 'y'): ?> checked <?php endif; ?> id="enable_user_twitter_registration" data-toggle="collapse" data-target="#twitter-login-settings">
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
                                                    <input type="checkbox" class="mw_option_field custom-control-input" option-group="users" name="enable_user_github_registration" value="y" <?php if ($enable_user_github_registration == 'y'): ?>checked<?php endif; ?> id="enable_user_github_registration" data-toggle="collapse" data-target="#github-login-settings">
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
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_linkedin_registration" option-group="users" id="enable_user_linkedin_registration" value="y" <?php if ($enable_user_linkedin_registration == 'y'): ?> checked <?php endif; ?> data-toggle="collapse" data-target="#linkedin-login-settings">
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
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_google_registration" option-group="users" id="enable_user_google_registration" value="y" <?php if ($enable_user_google_registration == 'y'): ?> checked <?php endif; ?> data-toggle="collapse" data-target="#google-login-settings">
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
                                                    <input type="checkbox" class="mw_option_field custom-control-input" name="enable_user_microweber_registration" option-group="users" id="enable_user_microweber_registration" value="y" <?php if ($enable_user_microweber_registration == 'y'): ?>checked<?php endif; ?> data-toggle="collapse" data-target="#mw-login-settings">
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

        <div class="card bg-none style-1 mb-0">
            <div class="card-body pt-3">
                <hr class="thin mt-0 mb-5"/>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Email notifications</h5>
                        <small class="text-muted">Register users can automatically receive an automatic email from you. See the settings and post your messages.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="control-label">Send email for a user registration</label>
                                            <small class="text-muted d-block mb-2">Do you want users to receive an e-mail when registering?</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="control-label">Select email template</label>
                                            <small class="text-muted d-flex justify-content-between align-items-center mb-2">Choose template to send for registred users.
                                                <button class="btn btn-sm btn-outline-primary">Add new email template</button>
                                            </small>
                                            <small class="text-muted d-block mb-2">If you add few emails for same functionality they will be showing in dropdown box.</small>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="Forgot password" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="New comment reply" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="New order" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="Sample" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
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

        <div class="card bg-none style-1 mb-0">
            <div class="card-body pt-3">
                <hr class="thin mt-0 mb-5"/>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Other settings</h5>
                        <small class="text-muted">Advanced setting where you can set different URL addresses.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Register URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the register page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Login URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the login page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Logout URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the logout page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Forgot password URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the forgot password page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <ul class="mw-ui-btn-nav mw-ui-btn-nav-fluid">
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"></a></li>
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e("Register e-mail"); ?></a></li>
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e("Privacy settings"); ?></a></li>
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e("Other"); ?></a></li>
        </ul>


        <div id="mw-user-fields-form-set" class="mw-user-fields-form-item">
            <div class="mw-ui-box mw-ui-box-content">

                <h2><?php _e("Send email on new user registration"); ?></h2>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-check" style="margin-right: 15px;">
                        <input name="register_email_enabled" class="mw_option_field" data-option-group="users" value="1" type="radio" <?php if (get_option('register_email_enabled', 'users') == 1): ?> checked="checked" <?php endif; ?> ><span></span><span><?php _e("Yes"); ?></span>
                    </label>
                    <label class="mw-ui-check">
                        <input name="register_email_enabled" class="mw_option_field" data-option-group="users" value="0" type="radio" <?php if (get_option('register_email_enabled', 'users') != 1): ?> checked="checked" <?php endif; ?> >
                        <span></span><span><?php _e("No"); ?></span>
                    </label>
                </div>

                <div class="mw-ui-field-holder mw-ui-row m-b-20">
                    <div class="mw-ui-col p-12">
                        <label class="mw-ui-label bold">
                            <?php _e("Select email template"); ?>
                        </label>

                        <module type="admin/mail_templates/select_template" option_group="users" mail_template_type="new_user_registration"/>

                        <br/>
                        <br/>
                        <a onclick="mw.register_email_send_test();" href="javascript:;" class="mw-ui-btn" style="float:left;width:330px;"><?php _e('Send Test Email'); ?></a>
                    </div>


                </div>

            </div>

            <hr>


            <a class="mw-ui-btn" href="javascript:;" onclick="$('#admin-forgot-pass-email-ctrl-holder').toggle()"><?php _e('Forgot password email settings'); ?></a>
            <br/>

            <div class="mw-ui-box mw-ui-box-content" id="admin-forgot-pass-email-ctrl-holder">


                <h2><?php _e("Send custom forgot password email"); ?></h2>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-check" style="margin-right: 15px;">
                        <input name="forgot_pass_email_enabled" class="mw_option_field" data-option-group="users" value="1" type="radio" <?php if (get_option('forgot_pass_email_enabled', 'users') == 1): ?> checked="checked" <?php endif; ?> >
                        <span></span><span><?php _e("Yes"); ?></span>
                    </label>

                    <label class="mw-ui-check">
                        <input name="forgot_pass_email_enabled" class="mw_option_field" data-option-group="users" value="0" type="radio" <?php if (get_option('forgot_pass_email_enabled', 'users') != 1): ?> checked="checked" <?php endif; ?> >
                        <span></span><span><?php _e("No"); ?></span>
                    </label>
                </div>

                <div class="mw-ui-field-holder mw-ui-row m-b-20">
                    <div class="mw-ui-col p-12">
                        <label class="mw-ui-label bold">
                            <?php _e("Select email template"); ?>
                        </label>

                        <module type="admin/mail_templates/select_template" option_group="users" mail_template_type="forgot_password"/>

                        <br/>
                        <br/>
                        <a onclick="mw.forgot_password_email_send_test();" href="javascript:;" class="mw-ui-btn" style="float:left;width:330px;"><?php _e('Send Test Email'); ?></a>
                    </div>

                </div>

            </div>
        </div>





        <div id="mw-admin-user-tabs-other-settings" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
            <div class="mw-ui-box mw-ui-box-content">
                <?php $captcha_disabled = get_option('captcha_disabled', 'users'); ?>
                <h2><?php _e("Register form settings"); ?></h2>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" option-group="users" name="captcha_disabled" <?php if ($captcha_disabled == 'y'): ?> checked <?php endif; ?> value="y">
                    <span></span><span><?php _e('Disable Captcha?'); ?></span>
                </label>

                <hr>

                <?php $disable_registration_with_temporary_email = get_option('disable_registration_with_temporary_email', 'users'); ?>

                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" option-group="users" name="disable_registration_with_temporary_email" <?php if ($disable_registration_with_temporary_email == 'y'): ?> checked <?php endif; ?> value="y">
                    <span></span><span><?php _e('Disable registration with temporary email?'); ?></span>
                </label>

                <hr>

                <div class="optional-fields mw-ui-field-holder">
                    <label class="mw-ui-check">
                        <input type="checkbox" value="y" <?php if ($form_show_first_name == 'y'): ?> checked <?php endif; ?> name="form_show_first_name" class="mw_option_field" option-group="users">
                        <span></span> <span><?php _e("Show first name field?"); ?></span>
                    </label>

                    <br/>
                    <br/>

                    <label class="mw-ui-check">
                        <input type="checkbox" value="y" <?php if ($form_show_last_name == 'y'): ?> checked <?php endif; ?> name="form_show_last_name" class="mw_option_field" option-group="users">
                        <span></span> <span><?php _e("Show last name field?"); ?></span>
                    </label>

                    <br/>
                    <br/>

                    <label class="mw-ui-check">
                        <input type="checkbox" value="y" <?php if ($form_show_password_confirmation == 'y'): ?> checked <?php endif; ?> name="form_show_password_confirmation" class="mw_option_field" option-group="users">
                        <span></span> <span><?php _e("Show password confirmation field?"); ?></span>
                    </label>

                    <br/>
                    <br/>

                    <label class="mw-ui-check">
                        <input type="checkbox" value="y" <?php if ($form_show_newsletter_subscription == 'y'): ?> checked <?php endif; ?> name="form_show_newsletter_subscription" class="mw_option_field" option-group="users">
                        <span></span> <span><?php _e("Show newsletter subscription checkbox?"); ?></span>
                    </label>
                </div>
            </div>

            <?php $login_captcha_enabled = get_option('login_captcha_enabled', 'users'); ?>

            <div class="mw-ui-box mw-ui-box-content">
                <h2><?php _e("Login form settings"); ?></h2>

                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" option-group="users" name="login_captcha_enabled" <?php if ($login_captcha_enabled == 'y'): ?> checked <?php endif; ?> value="y">
                    <span></span><span><?php _e('Require captcha to login?'); ?></span>
                </label>
            </div>

            <script>
                $(document).ready(function () {
                    $('.js-show-hide-btn').on('click', function () {
                        $('.js-show-hide').toggleClass('hidden');
                        $(this).hide();
                    });
                });
            </script>

            <div class="mw-ui-box mw-ui-box-content">
                <div class="js-show-hide hidden">
                    <h2><?php _e("Other settings"); ?></h2>
                    <hr/>

                    <h3><?php _e("Register URL"); ?></h3>
                    <p><?php _e("You can set a custom url for the register page"); ?></p>
                    <input name="register_url" class="mw_option_field mw-ui-field" type="text" option-group="users" value="<?php print get_option('register_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>

                    <h3><?php _e("Login URL"); ?></h3>
                    <p><?php _e("You can set a custom url for the login page"); ?></p>
                    <input name="login_url" class="mw_option_field mw-ui-field" type="text" option-group="users" value="<?php print get_option('login_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>

                    <h3><?php _e("Logout URL"); ?></h3>
                    <p><?php _e("You can set a custom url for the logout page"); ?></p>
                    <input name="logout_url" class="mw_option_field mw-ui-field" type="text" option-group="users" value="<?php print get_option('logout_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>

                    <h3><?php _e("Forgot password URL"); ?></h3>
                    <p><?php _e("You can set a custom url for the forgot password page"); ?></p>
                    <?php $checkout_url = get_option('forgot_password_url', 'users'); ?>
                    <input name="forgot_password_url" class="mw_option_field mw-ui-field" type="text" option-group="users" value="<?php print get_option('forgot_password_url', 'users'); ?>" placeholder="<?php _e("Use default"); ?>"/>
                </div>

                <button type="button" class="mw-ui-btn mw-ui-btn-info js-show-hide-btn m-t-10 m-b-10">Show Other settings</button>
            </div>
        </div>

    </div>

