<div class="mw-ui-box">

    <?php $form_btn_title = get_option('form_btn_title', $params['id']);
    if ($form_btn_title == false) {
        $form_btn_title = _e("Save new password", true);
    }

    ?>
    <?php //$rand = uniqid(); ?>
    <script type="text/javascript">

        $(document).ready(function () {
            mw.$('#user_reset_password_form<?php print $params['id'];  ?>').submit(function () {
                mw.form.post(mw.$('#user_reset_password_form<?php print $params['id'];  ?>'), '<?php print site_url('api') ?>/user_reset_password_from_link', function () {
                    var is_new_pass = mw.response('#user_reset_password_form<?php print $params['id'];  ?>', this);

                    if (is_new_pass == true) {

                        $('.reset-pass-form-wrap').hide();
                        $('.reset-pass-form-wrap-success').show();

                    }


                    // mw.reload_module('[data-type="categories"]');
                    // mw.reload_module('[data-type="pages"]');
                });

                return false;


            });

        });
    </script>

    <div class="box-head mw-ui-box-header">
        <h2><?php _e("Reset your password"); ?></h2>
    </div>
    <div class="mw-ui-box-content" id="form-holder<?php print $params['id']; ?>">
        <?php if (isset($_GET['reset_password_link']) == true): ?>
            <?php
                $reset = mw()->database_manager->escape_string($_GET['reset_password_link']);
                $data = User::where('password_reset_hash', $reset)->whereNotNull('password_reset_hash')->first();
                if ($data):
                ?>
                <form id="user_reset_password_form<?php print $params['id']; ?>" method="post" class="clearfix">
                    <div class="reset-pass-form-wrap">

                        <input type="hidden" name="password_reset_hash" value="<?php print $reset; ?>"/>
                        <input type="hidden" name="id" value="<?php print $data['id']; ?>"/>

                        <div class="control-group form-group mw-ui-field-holder">
                            <!-- <label class="control-label">Enter new password</label>-->
                            <div class="controls">
                                <input type="password" class="mw-ui-field field-full" placeholder="<?php _e("Choose a password"); ?>" name="pass1">
                            </div>
                        </div>
                        <div class="control-group form-group mw-ui-field-holder">
                            <!--        <label class="control-label">Repeat new password</label>
                            -->
                            <div class="controls">
                                <input type="password" class="mw-ui-field field-full" placeholder="<?php _e("Repeat the password"); ?>" name="pass2">
                            </div>
                        </div>


                        <div class="control-group form-group">
                            <div class="controls mw-ui-field-holder">
                                <div class="input-prepend mw-ui-field mw-ico-field">
                                   <module type="captcha" template="admin" />
                                </div>
                            </div>
                        </div>


                        <a class="btn btn-default btn-large pull-left mw-ui-btn" href="<?php print url_current(true, true); ?>"><?php _e("Back"); ?></a>
                        <button type="submit" class="btn btn-default btn-large pull-right btn-success mw-ui-btn mw-ui-btn-green"><?php print $form_btn_title ?></button>
                    </div>
                    <div style="clear: both"></div>
                </form>
                <div class="alert" style="margin-top: 20px;display: none;"></div>
            <?php else : ?>
                <div class="alert alert-warining text-center"><?php _e("Invalid or expired link"); ?>.
                    <br/><br/>
                    <a class="btn btn-default  btn-info" href="<?php print url_current(true, true); ?>"><?php _e("Go back"); ?></a>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="alert alert-warining text-center"><?php _e("You must click on the password reset link sent on your email"); ?>.<br/><br/>
                <a class="btn btn-default  btn-info" href="<?php print url_current(true, true); ?>"><?php _e("Go back"); ?></a></div>
        <?php endif; ?>
        <div class="reset-pass-form-wrap-success" style="display:none"><a class="btn btn-default  btn-primary"
                                                                          href="<?php print url_current(true, true); ?>"><?php _e("Click here to login with the new password"); ?></a></div>
    </div>

</div>
