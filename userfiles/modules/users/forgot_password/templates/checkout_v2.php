<script>mw.moduleCSS("<?php print modules_url(); ?>users/users_modules.css")</script>

<?php if (is_logged() == false): ?>
    <div class="iq-works-box text-left m-t-40 boots-form">
        <div class="box-static box-border-top padding-30" id="form-holder{rand}">
            <div class="box-title margin-bottom-20">
                <h5 class="size-20">
                    <?php if (!isset($form_title) or $form_title == false): ?>
                        <?php _lang("Enter your username or email", "templates/new-world"); ?>
                    <?php else: ?>
                        <?php print $form_title; ?>
                    <?php endif; ?>
                </h5>
            </div>

            <div class="alert alert-mini alert-danger margin-bottom-10" style="margin: 0;display: none;"></div>
            <br/>
            <form method="post" id="user_forgot_password_form{rand}" action="#" autocomplete="off">
                <div class="clearfix">
                    <!-- Email -->
                    <div class="form-group">
                        <div class="input-group mb-0">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            </div>
                            <input required="" type="text" class="form-control" placeholder="<?php _lang("Email or username", "templates/new-world"); ?>" name="username">
                        </div>
                        <b class="tooltip tooltip-bottom-right">"<?php _lang("Needed to verify your account", "templates/new-world"); ?></b>
                    </div>

                    <module type="captcha"/>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-check"></i> <?php print $form_btn_title; ?></button>
                    </div>
                </div>
            </form>

            <hr/>

        </div>
    </div>
<?php endif; ?>
