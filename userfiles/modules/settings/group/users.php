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

        mw.$('.optional-fields .mw-ui-check').bind('click', function () {
            mw.tools.toggleCheckbox(this.querySelector('input'));
        });

        mw.tabs({
            tabs: '.group-logins',
            nav: '.social-providers-list li',
            toggle: false,
            onclick: function (tab, e) {
                if (mw.tools.hasClass(e.target, 'mw-ui-check') || mw.tools.hasClass(e.target.parentNode, 'mw-ui-check')) {
                    mw.options.save(e.target.parentNode.querySelector('input'));
                }
                else {
                    mw.$(".mw-icon- li").removeClass("active");
                    if ($(this).hasClass("active")) {
                        $(this.parentNode).addClass("active");
                    }
                    else {

                    }
                    $(this.parentNode).removeClass("active");
                }
            }
        });


        mw.$('.social-providers-list .mw-ui-check').bind('mousedown', function () {
            mw.tools.toggleCheckbox(this.querySelector('input'));
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

    function checkAllowSocialsLogin() {
        var allowSocialsLoginSelect = $('select[name="allow_socials_login"]');
        if (allowSocialsLoginSelect.find('option:selected').val() == 'y') {
            $('.js-show-socials-registration').show();
        } else {
            $('.js-show-socials-registration').hide();
        }
    }

    function checkAllowSocialsLoginEnabled() {
        var allowSocialsLoginFB = $('input[name="enable_user_fb_registration"]');
        var allowSocialsLoginGoogle = $('input[name="enable_user_google_registration"]');
        var allowSocialsLoginGH = $('input[name="enable_user_github_registration"]');
        var allowSocialsLoginTwitter = $('input[name="enable_user_twitter_registration"]');
        var allowSocialsLoginLN = $('input[name="enable_user_linkedin_registration"]');
        var allowSocialsLoginMW = $('input[name="enable_user_microweber_registration"]');

        if (allowSocialsLoginFB.is(':checked')) {
            $('.js-check-fb').show();
        } else {
            $('.js-check-fb').hide();
        }
        if (allowSocialsLoginGoogle.is(':checked')) {
            $('.js-check-google').show();
        } else {
            $('.js-check-google').hide();
        }
        if (allowSocialsLoginGH.is(':checked')) {
            $('.js-check-gh').show();
        } else {
            $('.js-check-gh').hide();
        }
        if (allowSocialsLoginTwitter.is(':checked')) {
            $('.js-check-twitter').show();
        } else {
            $('.js-check-twitter').hide();
        }
        if (allowSocialsLoginLN.is(':checked')) {
            $('.js-check-ln').show();
        } else {
            $('.js-check-ln').hide();
        }
        if (allowSocialsLoginMW.is(':checked')) {
            $('.js-check-mw').show();
        } else {
            $('.js-check-mw').hide();
        }
    }

    $(document).ready(function () {
        checkAllowSocialsLoginEnabled();
        $("input[name^='enable_user_']").on('change', function () {
            checkAllowSocialsLoginEnabled();
        });

        checkAllowSocialsLogin();
        $('select[name="allow_socials_login"]').on('change', function () {
            checkAllowSocialsLogin();
        });
    })


</script>

<style type="text/css">
    .group-logins {
        display: none;
    }

    [class*="mw-social-ico"] {
        cursor: pointer;
    }

    .social-providers-list {
        clear: both;
        padding-bottom: 20px;
        overflow: hidden;
    }

    .social-providers-list [class*='mw-icon-'] {
        margin: 0 !important;
        top: -2px;
    }

    .social-providers-list .mw-icon-twitter {
        color: #55acee
    }

    .social-providers-list .mw-icon-facebook {
        color: #3B5999;
    }

    .social-providers-list .mw-icon-googleplus {
        color: #dd4b39;
    }

    .group-logins .mw-ui-label {
        padding-top: 20px;
    }

    .user-sign-setting-nav-item {
        margin-bottom: 5px;
    }

    .js-checkmark {
        position: absolute;
        font-size: 10px;
        top: -13px;
        display: none;
        right: 4px;
    }
</style>

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><span class="mai-login"></span> <?php _e("Login & Register"); ?></h2>
    </div>
</div>

<div class="admin-side-content">
    <div class="<?php print $config['module_class'] ?>">

        <ul class="mw-ui-btn-nav mw-ui-btn-nav-fluid">
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e("Register options"); ?></a></li>
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e("Register e-mail"); ?></a></li>
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e('Social links'); ?></a></li>
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e("Privacy settings"); ?></a></li>
            <li><a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"><?php _e("Other"); ?></a></li>
        </ul>


        <div id="mw-user-fields-form-set" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
            <div class="mw-ui-box mw-ui-box-content">
                <div class="mw-ui-row">
                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <?php $curent_val = get_option('enable_user_registration', 'users'); ?>
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Enable User Registration"); ?></label>
                                <select name="enable_user_registration" class="mw-ui-field mw_option_field w100" type="text" option-group="users">
                                    <option value="y" <?php if ($curent_val == 'y'): ?> selected="selected" <?php endif; ?>><?php _e("Yes"); ?></option>
                                    <option value="n" <?php if ($curent_val == 'n'): ?> selected="selected" <?php endif; ?>><?php _e("No"); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mw-ui-col">
                        <?php $allow_socials_login = get_option('allow_socials_login', 'users'); ?>
                        <div class="mw-ui-col-container">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Enable User Registration With Socials"); ?></label>
                                <select name="allow_socials_login" class="mw-ui-field mw_option_field w100" type="text" option-group="users">
                                    <option value="y" <?php if ($allow_socials_login == 'y'): ?> selected="selected" <?php endif; ?>><?php _e("Yes"); ?></option>
                                    <option value="n" <?php if ($allow_socials_login == 'n'): ?> selected="selected" <?php endif; ?>><?php _e("No"); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Registration Approval Required"); ?></label>
                                <select name="registration_approval_required" class="mw-ui-field mw_option_field w100" type="text" option-group="users">
                                    <option value="y" <?php if ($registration_approval_required == 'y'): ?> selected="selected" <?php endif; ?>><?php _e("Yes"); ?></option>
                                    <option value="n" <?php if ($registration_approval_required == 'n'): ?> selected="selected" <?php endif; ?>><?php _e("No"); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="js-show-socials-registration">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Allow Social Login with"); ?></label>
                    </div>

                    <ul class="social-providers-list mw-ui-btn-nav">
                        <li class="mw-ui-btn mw-ui-btn-big active">
                            <span class="js-checkmark js-check-fb"><i class="mw-icon-check"></i></span>
                            <span class="mw-icon-facebook login-tab-group active"></span>
                        </li>
                        <li class="mw-ui-btn mw-ui-btn-big">
                            <span class="js-checkmark js-check-google"><i class="mw-icon-check"></i></span>
                            <span class="mw-icon-googleplus login-tab-group"></span>
                        </li>
                        <li class="mw-ui-btn mw-ui-btn-big">
                            <span class="js-checkmark js-check-gh"><i class="mw-icon-check"></i></span>
                            <span class="mw-icon-social-github login-tab-group"></span>
                        </li>
                        <li class="mw-ui-btn mw-ui-btn-big">
                            <span class="js-checkmark js-check-twitter"><i class="mw-icon-check"></i></span>
                            <span class="mw-icon-twitter login-tab-group"></span>
                        </li>
                        <li class="mw-ui-btn mw-ui-btn-big">
                            <span class="js-checkmark js-check-ln"><i class="mw-icon-check"></i></span>
                            <span class="mw-icon-social-linkedin login-tab-group"></span>
                        </li>
                        <li class="mw-ui-btn mw-ui-btn-big">
                            <span class="js-checkmark js-check-mw"><i class="mw-icon-check"></i></span>
                            <span class="mw-icon-mw login-tab-group"></span>
                        </li>
                    </ul>

                    <div class="mw-ui-box mw-ui-box-content group-logins" style="display: block">
                        <label class="mw-ui-check">
                            <input type="checkbox" value="y" <?php if ($enable_user_fb_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_fb_registration" class="mw_option_field" option-group="users">
                            <span></span> <span><?php _e('Facebook login enabled?'); ?></span>
                        </label>

                        <hr>

                        <ol class="ol">
                            <li>
                                <?php _e("Api access"); ?>
                                <a class="mw-ui-link" target="_blank" href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a>
                            </li>
                            <li>
                                <?php _e("In"); ?>
                                <em><?php _e("Website with Facebook Login"); ?></em>
                                <?php _e("please enter"); ?>
                                <em><?php print site_url(); ?></em>
                            </li>
                            <li>
                                <?php _e("If asked for callback url - use"); ?>
                                <em><?php print api_link('social_login_process?provider=facebook') ?></em>
                            </li>
                        </ol>

                        <div class="mw-ui-field-holder" style="max-width: 400px;">
                            <label class="mw-ui-label"><?php _e("App ID/API Key"); ?></label>
                            <input name="fb_app_id" class="mw_option_field mw-ui-field mw-title-field w100" type="text" option-group="users" value="<?php print get_option('fb_app_id', 'users'); ?>"/>
                            <label class="mw-ui-label"><?php _e("App Secret"); ?></label>
                            <input name="fb_app_secret" class="mw_option_field mw-ui-field mw-title-field w100" type="text" option-group="users" value="<?php print get_option('fb_app_secret', 'users'); ?>"/>
                        </div>
                    </div>

                    <div class="mw-ui-box mw-ui-box-content group-logins">
                        <label class="mw-ui-check">
                            <input type="checkbox" value="y" <?php if ($enable_user_google_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_google_registration" class="mw_option_field" option-group="users">
                            <span></span> <span><?php _e('Google login enabled?'); ?></span>
                        </label>

                        <hr>

                        <ol class="ol">
                            <li>
                                <?php _e("Set your"); ?>
                                <em><?php _e("Api access"); ?></em> <a class="mw-ui-link" target="_blank" href="https://code.google.com/apis/console/">https://code.google.com/apis/console/</a>
                            </li>
                            <li>
                                <?php _e("In redirect URI  please enter"); ?>
                                <em><?php print api_link('social_login_process?provider=google') ?></em>
                            </li>
                        </ol>

                        <div class="mw-ui-field-holder" style="max-width: 400px;">
                            <label class="mw-ui-label"><?php _e("Client ID"); ?></label>
                            <input name="google_app_id" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('google_app_id', 'users'); ?>"/>
                            <label class="mw-ui-label"><?php _e("Client secret"); ?></label>
                            <input name="google_app_secret" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('google_app_secret', 'users'); ?>"/>
                        </div>
                    </div>

                    <div class="mw-ui-box mw-ui-box-content group-logins">
                        <label class="mw-ui-check">
                            <input type="checkbox" value="y" <?php if ($enable_user_github_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_github_registration" class="mw_option_field" option-group="users">
                            <span></span> <span><?php _e('Github login enabled?'); ?></span>
                        </label>

                        <hr>

                        <ol class="ol">
                            <li>
                                <?php _e("Register your application"); ?>
                                <a class="mw-ui-link" target="_blank" href="https://github.com/settings/applications/new">https://github.com/settings/applications/new</a>
                            </li>
                            <li>
                                <?php _e("In"); ?>
                                <em><?php _e("Main URL"); ?></em>
                                <?php _e("enter"); ?>
                                <em><?php print site_url() ?></em>
                            </li>
                            <li>
                                <?php _e("In"); ?>
                                <em><?php _e("Callback URL"); ?></em>
                                <?php _e("enter"); ?>
                                <em><?php print api_link('social_login_process?provider=github') ?></em>
                            </li>
                        </ol>

                        <div class="mw-ui-field-holder" style="max-width: 400px;">
                            <label class="mw-ui-label"><?php _e("Client ID"); ?></label>
                            <input name="github_app_id" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('github_app_id', 'users'); ?>"/>
                            <label class="mw-ui-label"><?php _e("Client secret"); ?></label>
                            <input name="github_app_secret" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('github_app_secret', 'users'); ?>"/>
                        </div>
                    </div>

                    <div class="mw-ui-box mw-ui-box-content group-logins">
                        <label class="mw-ui-check">
                            <input type="checkbox" value="y" <?php if ($enable_user_twitter_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_twitter_registration" class="mw_option_field" option-group="users">
                            <span></span> <span><?php _e('Twitter login enabled?'); ?></span>
                        </label>

                        <hr>

                        <ol class="ol">
                            <li>
                                <?php _e("Register your application"); ?>
                                <a class="mw-ui-link" target="_blank" href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a>
                            </li>
                            <li>
                                <?php _e("In"); ?>
                                <em><?php _e("Website"); ?></em>
                                <?php _e("enter"); ?>
                                <em><?php print site_url(); ?></em>
                            </li>
                            <li>
                                <?php _e("In"); ?>
                                <em><?php _e("Callback URL"); ?></em>
                                <?php _e("enter"); ?>
                                <em><?php print api_link('social_login_process?provider=twitter') ?></em>
                            </li>
                        </ol>

                        <div class="mw-ui-field-holder" style="max-width: 400px;">
                            <label class="mw-ui-label"><?php _e("Consumer key"); ?></label>
                            <input name="twitter_app_id" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('twitter_app_id', 'users'); ?>"/>
                            <label class="mw-ui-label"><?php _e("Consumer secret"); ?></label>
                            <input name="twitter_app_secret" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('twitter_app_secret', 'users'); ?>"/>
                        </div>
                    </div>

                    <div class="mw-ui-box mw-ui-box-content group-logins">
                        <label class="mw-ui-check">
                            <input type="checkbox" value="y" <?php if ($enable_user_linkedin_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_linkedin_registration" class="mw_option_field" option-group="users">
                            <span></span> <span><?php _e('Linked-in login enabled?'); ?></span>
                        </label>

                        <hr>

                        <ol class="ol">
                            <li>
                                <?php _e("Register your application"); ?>
                                <a class="mw-ui-link" target="_blank" href="https://www.linkedin.com/secure/developer">https://www.linkedin.com/secure/developer</a>
                            </li>
                            <li>
                                <?php _e("In"); ?>
                                <em><?php _e("Website"); ?></em>
                                <?php _e("enter"); ?>
                                <em><?php print site_url(); ?></em></li>
                            <li>
                                <?php _e("In"); ?>
                                <em><?php _e("Callback URL"); ?></em>
                                <?php _e("enter"); ?>
                                <em><?php print api_link('social_login_process?provider=linkedin') ?></em>
                            </li>
                        </ol>

                        <div class="mw-ui-field-holder" style="max-width: 400px;">
                            <label class="mw-ui-label"><?php _e("Client ID"); ?></label>
                            <input name="linkedin_app_id" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('linkedin_app_id', 'users'); ?>"/>
                            <label class="mw-ui-label"><?php _e("Client Secret"); ?></label>
                            <input name="linkedin_app_secret" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('linkedin_app_secret', 'users'); ?>"/>
                        </div>
                    </div>

                    <div class="mw-ui-box mw-ui-box-content group-logins">
                        <label class="mw-ui-check">
                            <input type="checkbox" value="y" <?php if ($enable_user_microweber_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_microweber_registration" class="mw_option_field" option-group="users">
                            <span></span> <span><?php _e('Microweber login enabled?'); ?></span>
                        </label>

                        <hr>

                        <ol class="ol">
                            <li>
                                <?php _e("Please enter your credentials for Microweber Login Server"); ?>
                            </li>
                        </ol>

                        <div class="mw-ui-field-holder" style="max-width: 400px;">

                            <?php
                            $microweber_app_url = get_option('microweber_app_url', 'users');
                            if (empty($microweber_app_url)) {
                                $microweber_app_url = 'https://mwlogin.com';
                            }
                            ?>

                            <label class="mw-ui-label"><?php _e("Server URL"); ?></label>
                            <input name="microweber_app_url" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print $microweber_app_url; ?>"/>

                            <label class="mw-ui-label"><?php _e("Client ID"); ?></label>
                            <input name="microweber_app_id" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('microweber_app_id', 'users'); ?>"/>

                            <label class="mw-ui-label"><?php _e("Client secret"); ?></label>
                            <input name="microweber_app_secret" class="mw_option_field mw-ui-field mw-title-field w100" style="" type="text" option-group="users" value="<?php print get_option('microweber_app_secret', 'users'); ?>"/>

                        </div>
                    </div>
                </div>

                <script>
                    showLoginURLSettings = function () {
                        var el = mwd.getElementById('user-login-urls-set');
                        $(el).toggle();

                        if (el.style.display == 'block') {
                            mw.tools.scrollTo(el);
                        }
                    }

                    $(document).ready(function () {
                        mw.tabs({
                            nav: ".user-sign-setting-nav-item",
                            tabs: ".mw-user-fields-form-item",
                            toggle: true
                        })
                    })
                </script>
            </div>
        </div>


        <div id="mw-user-fields-form-set" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
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

					  	<module type="admin/mail_templates/select_template" option_group="users" mail_template_type="new_user_registration" />

					  	<br />
					  	<br />
					  	<a onclick="mw.register_email_send_test();" href="javascript:;" class="mw-ui-btn" style="float:left;width:330px;"><?php _e('Send Test Email'); ?></a>
				    </div>


				</div>

            </div>

            <hr>


            <a class="mw-ui-btn" href="javascript:;" onclick="$('#admin-forgot-pass-email-ctrl-holder').toggle()"><?php _e('Forgot password email settings'); ?></a>
			<br />

            <div class="mw-ui-box mw-ui-box-content" id="admin-forgot-pass-email-ctrl-holder" style="display:none">


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

					  	<module type="admin/mail_templates/select_template" option_group="users" mail_template_type="forgot_password" />

					  	<br />
					  	<br />
					  	<a onclick="mw.forgot_password_email_send_test();" href="javascript:;" class="mw-ui-btn" style="float:left;width:330px;"><?php _e('Send Test Email'); ?></a>
				    </div>

				</div>

            </div>
        </div>

        <div id="mw-global-fields-social-profile-set" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
            <div class="mw-ui-box mw-ui-box-content">
                <module type="social_links/admin" module-id="website"/>
            </div>
        </div>

        <div id="mw-profile-privacy-settings" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
            <div class="mw-ui-box mw-ui-box-content">
                <h2><?php _e("User privacy settings"); ?></h2>

                <h4><?php _e("Users must agree to the Terms and Conditions"); ?></h4>

                <label class="mw-ui-check" style="margin-right: 15px;">
                    <input name="require_terms" class="mw_option_field" data-option-group="users" value="0" type="radio" <?php if (get_option('require_terms', 'users') != 1): ?> checked="checked" <?php endif; ?> >
                    <span></span><span><?php _e("No"); ?></span>
                </label>

                <label class="mw-ui-check">
                    <input name="require_terms" class="mw_option_field" data-option-group="users" value="1" type="radio" <?php if (get_option('require_terms', 'users') == 1): ?> checked="checked" <?php endif; ?> >
                    <span></span><span><?php _e("Yes"); ?></span>
                </label>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("User consent text"); ?></label>
                    <?php
                    $terms_label = get_option('terms_label', 'users');
                    $terms_label_cleared = str_replace('&nbsp;', '', $terms_label);
                    $terms_label_cleared = strip_tags($terms_label_cleared);
                    $terms_label_cleared = mb_trim($terms_label_cleared);

                    if ($terms_label_cleared == '') {
                        $terms_label = 'I agree with your <a href="' . site_url() . 'terms" target="_blank">Terms and Conditions</a> and  <a href="' . site_url() . 'privacy" target="_blank">Privacy Policy</a>';
                    }
                    ?>
                    <textarea class="mw-ui-field mw_option_field" data-option-group="users" id="terms_label" name="terms_label"><?php print $terms_label; ?></textarea>
                    <script>
                        $(document).ready(function () {
                            myEditor = mw.editor({element: '#terms_label',height: 200});
                        });
                    </script>
                </div>
                <hr>

                <?php event_trigger('website.privacy_settings') ?>
                <hr>
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
</div>
