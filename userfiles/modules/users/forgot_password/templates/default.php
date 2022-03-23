
<?php $rand = uniqid(); ?>

<script>mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css")</script>
<script>


    var mwHandleForgotPassword = function (event) {
        var form = event.target;
        event.preventDefault();
        if(form._submitting) return;
        form._submitting = true;
        $(form).addClass('loading');
        mw.tools.disable(mw.$("[type='submit']", form));
        mw.form.post(form, '<?php print site_url('api') ?>/user_send_forgot_password', function (a) {
            var dialog = mw.alert().container.querySelector('.mw-alert-holder');
            dialog.innerHTML = ''
            mw.response(dialog, this);
            $(form).removeClass('loading');
            mw.tools.enable(mw.$("[type='submit']", form));
            form._submitting = false;
        });
    }


</script>

<div class="module-forgot-password well">
    <?php if (!isset($show_login_link) or $show_login_link != 'n'): ?>
        <div class="box-head">
            <h2><a href="<?php print register_url(); ?>">
                    <?php _e("New Registration"); ?>
                </a> or <a href="<?php print login_url(); ?>">
                    <?php _e("Login"); ?>
                </a></h2>
        </div>
    <?php endif; ?>
    <div id="form-holder<?php echo $rand;?>">
        <h4>
            <?php if (!isset($form_title) or $form_title == false): ?>
                <?php _e("Enter your username or email"); ?>
            <?php else: ?>
                <?php print $form_title; ?>
            <?php endif; ?>
        </h4>
        <form onsubmit="mwHandleForgotPassword(event)" method="post" class="clearfix">
            <div class="control-group form-group">
                <div class="controls">
                    <input type="text" class="large-field form-control" name="username" placeholder="<?php _e("Email or Username"); ?>">
                </div>
            </div>
            <?php if (isset($captcha_disabled) and $captcha_disabled == false): ?>
                <div class="mw-ui-row vertical-middle captcha-row">
                    <module type="captcha" template="admin"/>
                </div>
            <?php endif; ?>
            <br>
            <button type="submit" class="btn btn-default pull-right"><?php print $form_btn_title; ?></button>
        </form>
        <div class="alert" style="margin: 0;display: none;"></div>
    </div>
</div>
