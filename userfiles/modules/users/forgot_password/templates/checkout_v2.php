<?php $rand = uniqid(); ?>
<script>mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css")</script>

<?php if (is_logged() == false): ?>
    <div class="iq-works-box text-start text-left mt-5 boots-form col-md-12">

        <div class="d-flex pb-4">
            <h4><?php _e("Forgot password?"); ?></h4>
            <a href="<?php echo route('checkout.login'); ?>" class="ml-auto align-self-center"><?php _e('Login'); ?></a>
            <a href="<?php echo route('checkout.register'); ?>" class="ml-auto align-self-center"><?php _e('Register'); ?></a>
        </div>

        <div class="box-static box-border-top" id="form-holder<?php echo $rand;?>">

            <div class="alert alert-mini alert-danger margin-bottom-10" style="margin: 0;display: none;"></div>
            <br/>
            <form method="post" id="user_forgot_password_form<?php echo $rand;?>" action="#" autocomplete="off">
                <div class="clearfix">
                    <!-- Email -->
                    <div class="form-group">

                        <label class="control-label">
                        <?php if (!isset($form_title) or $form_title == false): ?>
                            <?php _lang("Enter your username or email", "templates/new-world"); ?>
                        <?php else: ?>
                            <?php print $form_title; ?>
                        <?php endif; ?>
                       </label>

                        <div class="input-group mb-0">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            </div>
                            <input required="" type="text" class="form-control" placeholder="<?php _lang("Email or username", "templates/new-world"); ?>" name="username">
                        </div>
                        <b class="tooltip tooltip-bottom-right">"<?php _lang("Needed to verify your account", "templates/new-world"); ?></b>
                    </div>


                    <?php if (isset($captcha_disabled) and $captcha_disabled == false): ?>
                        <module type="captcha"/>
                    <?php endif; ?>




                </div>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-outline-primary btn-block" type="submit"> <?php print $form_btn_title; ?></button>
                    </div>
                </div>
            </form>


        </div>
    </div>
<?php endif; ?>
