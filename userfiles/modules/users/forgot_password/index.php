
<?php $rand = uniqid(); ?>

<div class="mw-forgot-pass-wrapper">
    <?php if (isset($_GET['reset_password_link'])): ?>
    <module type="users/forgot_password/reset_password"/>
    <?php else: ?>
        <?php $user = user_id(); ?>
        <?php $have_social_login = false; ?>
        <?php $form_btn_title = get_option('form_btn_title', $params['id']);
        if ($form_btn_title == false) {
            $form_btn_title = _e("Reset password", true);
        }
        $form_title = get_option('form-title', $params['id']);
        $show_login_link = get_option('show-login-link', $params['id']);
        ?>

        <script type="text/javascript">
            mw.require('forms.js', true);
            mw.require('url.js', true);

            formenabled = true;

            $(document).ready(function () {

                mw.$('#user_forgot_password_form<?php echo $rand;?>').submit(function () {

                    if (formenabled) {
                        formenabled = false;
                        var form = this;
                        $(form).addClass('loading');
                        mw.tools.disable(mw.$("[type='submit']", form));

                        mw.form.post(mw.$('#user_forgot_password_form<?php echo $rand;?>'), '<?php print route('api.user.password.email') ?>', function (a) {
                            mw.response('#form-holder<?php echo $rand;?>', this);
                            formenabled = true;
                            if(this.error  && this.message){
                                mw.notification.error(this.message);
                            } else if(this.message){
                                mw.notification.msg(this.message);
                            }
                            $(form).removeClass('loading');
                            mw.tools.enable(mw.$("[type='submit']", form));
                        },false, function (a) {

                            mw.notification.msg(this);

                            formenabled = true;
                            $(form).removeClass('loading');
                            mw.tools.enable(mw.$("[type='submit']", form));

                        });
                    }
                    return false;
                });

            });
        </script>
        <?php
		$captcha_disabled = get_option('captcha_disabled', 'users') == 'y';
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
</div>
