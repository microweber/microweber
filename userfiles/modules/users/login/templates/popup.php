<?php

/*

type: layout

name: Login Popup

description: Login Popup

*/

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>

<div id="mw-login-popup">
    <div id="user_login_holder_<?php print $params['id'] ?>">
        <form method="post" id="pop-up-login" class="clearfix" action="#">
            <div class="form-group">
                <input class="form-control"  name="username" type="text" placeholder="<?php _e("Email"); ?>"/>
            </div>

            <div class="form-group">
                <input class="form-control" name="password" type="password" placeholder="<?php _e("Password"); ?>"/>
            </div>

            <div class="text-end">
                <input class="btn btn-primary" type="submit" value="<?php _e("Login"); ?>"/>
            </div>

            <div class="alert" style="margin: 0;display: none;"></div>

            <?php if (isset($_GET['redirect'])): ?>
                <input type="hidden" value="<?php echo mw()->format->clean_xss($_GET['redirect']); ?>" name="redirect">
            <?php endif; ?>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        mw.$("#pop-up-login").submit(function () {
            mw.form.post($(this), '<?php print api_link('user_login') ?>', function (a, b) {

                mw.response('#user_login_<?php print $params['id'] ?>', this);
                if (typeof this.success === 'string') {
                    mw.$("#session_modal").remove();
                    mw.$(".mw_overlay").remove();
                    mw.notification.success("<?php _ejs("You are now logged in"); ?>.");
                    return false;
                }
                mw.notification.msg(this, 5000);
            });

            return false;
        });
    })
</script>
