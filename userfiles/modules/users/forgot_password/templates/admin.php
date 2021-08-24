<?php $rand = uniqid(); ?>
<div id="form-holder<?php echo $rand;?>">
    <div class="alert" style="margin: 0;display: none;"></div>

    <form id="user_forgot_password_form<?php echo $rand;?>" method="post" class="clearfix">
        <div class="form-group">
            <label>E-mail or Username:</label>
            <input type="text" class="form-control" name="username" placeholder="<?php _e("Enter Email or Username"); ?>">
        </div>

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
        <div class="form-group text-end">
            <button type="submit" id="submit" class="btn btn-outline-primary btn-sm"><?php print $form_btn_title; ?></button>
        </div>
    </form>
</div>
