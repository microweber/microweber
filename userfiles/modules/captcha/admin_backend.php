<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
 ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <div class="mw-module-admin-wrap">
            <style>
                .js-recaptcha-v2 {
                    display: none;
                }

                .js-recaptcha-v3 {
                    display: none;
                }
            </style>
             <script type="text/javascript">
                $(document).ready(function () {
                    mw.options.form('.<?php print $config['module_class'] ?>', function () {
                        mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
                    });

                    captchaSettingsPreview();

                    $('.js-select-captcha-provider').on('change', function () {
                        var captcha_provider = $(this).val();
                        captchaSettingsPreview(captcha_provider);

                    });
                });

                function captchaSettingsPreview(captcha_provider) {

                    if(typeof captcha_provider == 'undefined'){
                        captcha_provider = $('.js-select-captcha-provider option:selected').val();
                    }
                    if (captcha_provider == 'microweber') {
                        $('.js-recaptcha-v3').hide();
                        $('.js-recaptcha-v2').hide();
                    }

                    if (captcha_provider == 'google_recaptcha_v2') {
                        $('.js-recaptcha-v3').hide();
                        $('.js-recaptcha-v2').slideDown();
                    }

                    if (captcha_provider == 'google_recaptcha_v3') {
                        $('.js-recaptcha-v3').slideDown();
                        $('.js-recaptcha-v2').hide();
                    }
                }
            </script>

            <div id="mw_index_captcha_form">
                <div class="<?php print $config['module_class'] ?>">
                    <div class="form-group">
                        <label class="control-label"><?php _e("Captcha provider"); ?></label>

                        <select class="mw_option_field js-select-captcha-provider selectpicker" data-width="100%" name="provider" option-group="captcha">
                            <option value="microweber"><?php _e("Select"); ?></option>
                            <option value="google_recaptcha_v2" <?php if (get_option('provider', 'captcha') == 'google_recaptcha_v2'): ?>selected="selected"<?php endif; ?>><?php _e("Google ReCaptcha V2"); ?></option>
                            <option value="google_recaptcha_v3" <?php if (get_option('provider', 'captcha') == 'google_recaptcha_v3'): ?>selected="selected"<?php endif; ?>><?php _e("Google ReCaptcha V3"); ?></option>
                            <option value="microweber" <?php if (get_option('provider', 'captcha') == 'microweber'): ?>selected="selected"<?php endif; ?>><?php _e("Captcha"); ?></option>
                        </select>
                    </div>

                    <div class="js-recaptcha-v2">
                        <div class="form-group">
                            <label class="control-label">Google Recaptcha V2 Site Key</label>
                            <input type="text" name="recaptcha_v2_site_key" option-group="captcha" value="<?php echo get_option('recaptcha_v2_site_key', 'captcha'); ?>" class="mw_option_field form-control"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Google ReCaptcha V2 Secret Key</label>
                            <input type="text" name="recaptcha_v2_secret_key" option-group="captcha" value="<?php echo get_option('recaptcha_v2_secret_key', 'captcha'); ?>" class="mw_option_field form-control"/>
                        </div>
                    </div>

                    <div class="js-recaptcha-v3">
                        <div class="form-group">
                            <label class="control-label">Google Recaptcha V3 Site Key</label>
                            <input type="text" name="recaptcha_v3_site_key" option-group="captcha" value="<?php echo get_option('recaptcha_v3_site_key', 'captcha'); ?>" class="mw_option_field form-control"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Google ReCaptcha V3 Secret Key</label>
                            <input type="text" name="recaptcha_v3_secret_key" option-group="captcha" value="<?php echo get_option('recaptcha_v3_secret_key', 'captcha'); ?>" class="mw_option_field form-control"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Google ReCaptcha V3 Score</label>
                            <input type="text" placeholder="0.5" name="recaptcha_v3_score" option-group="captcha" value="<?php echo get_option('recaptcha_v3_score', 'captcha'); ?>" class="mw_option_field form-control"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
