<?php $rand = uniqid(); ?>
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
<div id="form-holder<?php echo $rand;?>">
    <div class="alert" style="margin: 0;display: none;"></div>

    <form onsubmit="mwHandleForgotPassword(event)" id="user_forgot_password_form<?php echo $rand;?>" method="post" class="clearfix">
        <div class="form-group">
            <label>E-mail:</label>
            <input type="text" class="form-control" name="email" placeholder="<?php _e("Enter your account email"); ?>">
        </div>

        <?php if (isset($captcha_disabled) and $captcha_disabled == false): ?>
        <div class="form-group">
            <div class="row">
                <div class="col-auto">
                    <img class="img-fluid" src="<?php print api_link('captcha') ?>?w=100&h=40" onclick="mw.tools.refresh_image(this);"/>
                </div>
                <div class="col">
                    <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="form-control" name="captcha">
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group text-end text-right">
            <button type="submit" id="submit" dusk="reset-password-button" class="btn btn-outline-primary btn-sm"><?php print $form_btn_title; ?></button>
        </div>
    </form>
</div>
