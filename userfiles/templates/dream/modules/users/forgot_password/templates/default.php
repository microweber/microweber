<script>mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css")</script>


<?php if (is_logged() == false): ?>
    <div class="col-md-4 col-md-offset-4">
        <div class="box-static box-border-top padding-30" id="form-holder{rand}">
            <div class="box-title margin-bottom-30">
                <h2 class="size-20">
                    <?php if (!isset($form_title) or $form_title == false): ?>
                        <?php _e("Enter your username or email"); ?>
                    <?php else: ?>
                        <?php print $form_title; ?>
                    <?php endif; ?>
                </h2>
            </div>

            <div class="alert alert-mini alert-danger margin-bottom-30" style="margin: 0;display: none;"></div>
            <br/>
            <form class="sky-form" method="post" id="user_forgot_password_form{rand}" action="#" autocomplete="off">
                <div class="clearfix">
                    <!-- Email -->
                    <div class="form-group">
                        <label><?php _e("Email or username"); ?></label>
                        <label class="input margin-bottom-10">
                            <i class="ico-append fa fa-envelope"></i>
                            <input required="" type="text" name="username">
                            <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                        </label>
                    </div>

                    <module type="captcha" />
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> <?php print $form_btn_title; ?></button>
                    </div>
                </div>
            </form>

            <hr/>

        </div>
    </div>
<?php endif; ?>