<?php only_admin_access(); ?>

<div class="mw-module-admin-wrap">
    <?php if (isset($params['backend'])): ?>
        <module type="admin/modules/info"/>
    <?php endif; ?>

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
            $('.js-select-captcha-provider').change(function () {
                captchaSettingsPreview();
            });

        });

        function captchaSettingsPreview() {

            captcha_provider = $('.js-select-captcha-provider').val();

            $('.js-recaptcha-v2').hide();
            $('.js-recaptcha-v3').hide();

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

    <div id="mw_index_captcha_form" class="admin-side-content" style="max-width:100%;">
        <div class="<?php print $config['module_class'] ?>">

            <b><?php _e("Captcha provider"); ?></b>

            <select class="mw-ui-field mw_option_field mw-full-width js-select-captcha-provider" name="provider" option-group="captcha">
                <option value="microweber">Select</option>
                <option value="google_recaptcha_v2"  <?php if(get_option('provider', 'captcha') == 'google_recaptcha_v2'): ?>selected="selected"<?php endif; ?>>Google ReCaptcha V2</option>
                <option value="google_recaptcha_v3"  <?php if(get_option('provider', 'captcha') == 'google_recaptcha_v3'): ?>selected="selected"<?php endif; ?>>Google ReCaptcha V3</option>
                <option value="microweber" <?php if(get_option('provider', 'captcha') == 'microweber'): ?>selected="selected"<?php endif; ?>>Microweber Captcha</option>
            </select>

            <br /><br />

            <div class="js-recaptcha-v2">
                <div>
                    <b>Google Recaptcha V2 Site Key</b>
                    <input type="text" name="recaptcha_v2_site_key" option-group="captcha" value="<?php echo get_option('recaptcha_v2_site_key', 'captcha'); ?>" class="mw-ui-field mw_option_field mw-full-width" />
                </div>

                <br />

                <div>
                    <b>Google ReCaptcha V2 Secret Key</b>
                    <input type="text" name="recaptcha_v2_secret_key" option-group="captcha" value="<?php echo get_option('recaptcha_v2_secret_key', 'captcha'); ?>" class="mw-ui-field mw_option_field mw-full-width" />
                </div>
            </div>

            <div class="js-recaptcha-v3">
                <div>
                    <b>Google Recaptcha V3 Site Key</b>
                    <input type="text" name="recaptcha_v3_site_key" option-group="captcha" value="<?php echo get_option('recaptcha_v3_site_key', 'captcha'); ?>" class="mw-ui-field mw_option_field mw-full-width" />
                </div>

                <br />

                <div>
                    <b>Google ReCaptcha V3 Secret Key</b>
                    <input type="text" name="recaptcha_v3_secret_key" option-group="captcha" value="<?php echo get_option('recaptcha_v3_secret_key', 'captcha'); ?>" class="mw-ui-field mw_option_field mw-full-width" />
                </div>

                <br />

                <div>
                    <b>Google ReCaptcha V3 Score</b>
                    <input type="text" placeholder="0.5" name="recaptcha_v3_score" option-group="captcha" value="<?php echo get_option('recaptcha_v3_score', 'captcha'); ?>" class="mw-ui-field mw_option_field mw-full-width" />
                </div>
            </div>


        </div>
    </div>
</div>
