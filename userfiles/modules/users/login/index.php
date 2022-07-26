<?php if (isset($_GET['reset_password_link'])): ?>
    <module type="users/forgot_password"/>
<?php else: ?>

    <script type="text/javascript">
        mw.require('forms.js', true);
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            if (!mw.$('#user_login_<?php print $params['id'] ?>').hasClass("custom-submit")) {
                mw.$('#user_login_<?php print $params['id'] ?>').submit(function () {
                    var subm = mw.$('[type="submit"]', this);
                    if (!subm.hasClass("disabled")) {
                        mw.tools.disable(subm, '<?php _e("Signing in..."); ?>');
                        mw.form.post(mw.$('#user_login_<?php print $params['id'] ?>'), '<?php print api_link('user_login'); ?>', function (a, b) {

                            // mw.response('#user_login_<?php print $params['id'] ?>',this);

                            if (typeof this.message === 'string') {
                                mw.notification.error(this.message, 2000);
                                mw.tools.enable(subm);
                            }

                            if (typeof this.error === 'string') {
                                mw.notification.error(this.error, 2000);
                                mw.tools.enable(subm);
                            }

                            if (typeof this.success === 'string') {
                                var c = mw.$('#user_login_<?php print $params['id'] ?>').dataset("callback");
                                if (c == undefined || c == '') {
                                    var c = mw.$('#<?php print $params['id'] ?>').dataset("callback");
                                }
                                <?php if(!isset($params['return']) and isset($_REQUEST['return'])): ?>
                                <?php $params['return'] = $_REQUEST['return']; ?>
                                <?php endif; ?>
                                <?php if(isset($params['return'])): ?>
                                <?php
                                $goto = urldecode($params['return']);
                                $goto = mw()->format->clean_xss($goto);

                                if (stristr($goto, "http://") == false and stristr($goto, "https://") == false) {
                                    $goto = site_url($goto);
                                }
                                ?>
                                window.location.href = '<?php print $goto; ?>';
                                return false;
                                <?php else:  ?>

                                if (typeof this.return === 'string') {
                                    window.location.href = this.return;
                                    return false;
                                } else if (typeof this.redirect === 'string') {
                                    window.location.href = this.redirect;
                                    return false;

                                }

                                mw.reload_module('[data-type="<?php print $config['module'] ?>"]', function () {
                                    if (c == '') {
                                        window.location.reload();
                                        // window.location.href ='<?php print site_url(); ?>';
                                    }
                                    else {
                                        if (typeof window[c] === 'function') {
                                            window[c]();
                                        } else {
                                            //window.location.href ='<?php print site_url(); ?>';
                                            window.location.reload();
                                        }
                                    }
                                });

                                <?php endif; ?>

                                mw.notification.msg(this, 5000);
                                mw.tools.enable(subm);

                                return false;
                            }

                        });
                    }
                    return false;
                });
            }
        });
    </script>


    <?php


    $form_btn_title = get_option('form_btn_title', $params['id']);
    if ($form_btn_title == false) {
        $form_btn_title = 'Login';
    }

    $input = [];
    if(!empty($_POST) or !empty($_GET)){
        $input = mw()->format->clean_xss(\Request::all());
    }
    $login_captcha_enabled = get_option('login_captcha_enabled', 'users') == 'y';

    # Login Providers
    $facebook = get_option('enable_user_fb_registration', 'users') == 'y';
    $twitter = get_option('enable_user_twitter_registration', 'users') == 'y';
    $google = get_option('enable_user_google_registration', 'users') == 'y';
    $windows = get_option('enable_user_windows_live_registration', 'users') == 'y';
    $github = get_option('enable_user_github_registration', 'users') == 'y';
    $microweber_login = get_option('enable_user_microweber_registration', 'users') == 'y';
    if ($facebook or $twitter or $google or $windows or $github) {
        $have_social_login = true;
    } else {
        $have_social_login = false;
    }

    $allow_socials_login = get_option('allow_socials_login', 'users');
    if ($allow_socials_login == 'n') {
        $have_social_login = false;
    }

    $module_template = get_option('data-template', $params['id']);
    if ($module_template == false and isset($params['template'])) {
        $module_template = $params['template'];
    }

    if ($module_template != false) {
        $template_file = module_templates($config['module'], $module_template);
    } else {
        $template_file = module_templates($config['module'], 'default');
    }

    if (isset($template_file) and is_file($template_file) != false) {
        include($template_file);
    } else {
        $template_file = module_templates($config['module'], 'default');
        include($template_file);
    }
    ?>
<?php endif; ?>
