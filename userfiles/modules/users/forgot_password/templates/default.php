<script>mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css")</script>

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
    <div id="form-holder{rand}">
        <h4>
            <?php if (!isset($form_title) or $form_title == false): ?>
                <?php _e("Enter your username or email"); ?>
            <?php else: ?>
                <?php print $form_title; ?>
            <?php endif; ?>
        </h4>
        <form id="user_forgot_password_form{rand}" method="post" class="clearfix">
            <div class="control-group form-group">
                <div class="controls">
                    <input type="text" class="large-field form-control" name="username" placeholder="<?php _e("Email or Username"); ?>">
                </div>
            </div>
            <div class="mw-ui-row vertical-middle captcha-row">
                <module type="captcha"/>
            </div>
            <br>
            <button type="submit" class="btn btn-default pull-right"><?php print $form_btn_title; ?></button>
        </form>
        <div class="alert" style="margin: 0;display: none;"></div>
    </div>
</div>
