<?php only_admin_access(); ?>

<?php if (!isset($params['live_edit'])): ?>
<div class="mw-module-admin-wrap">
    <?php if (isset($params['backend'])): ?>
        <module type="admin/modules/info"/>
    <?php endif; ?>

    <script type="text/javascript">
        $(document).ready(function () {
            mw.options.form('.<?php print $config['module_class'] ?>', function () {
                mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
            });
        });
    </script>

    <div id="mw_index_captcha_form" class="admin-side-content" style="max-width:100%;">
        <div class="<?php print $config['module_class'] ?>">

            <b><?php _e("Captcha provider"); ?></b>

            <select class="mw-ui-field mw_option_field mw-full-width" name="provider" option-group="captcha"> 
                <option value="microweber">Select</option>
                <option value="google_recaptcha_v2"  <?php if(get_option('provider', 'captcha') == 'google_recaptcha_v2'): ?>selected="selected"<?php endif; ?>>Google ReCaptcha V2</option>
                <option value="microweber" <?php if(get_option('provider', 'captcha') == 'microweber'): ?>selected="selected"<?php endif; ?>>Microweber Captcha</option>
            </select>

            <br /><br />

            <div>
                <b>Google Recaptcha Site Key</b>
                <input type="text" name="recaptcha_v2_site_key" option-group="captcha" value="<?php echo get_option('recaptcha_v2_site_key', 'captcha'); ?>" class="mw-ui-field mw_option_field mw-full-width" />
            </div>

            <br />

            <div>
                <b>Google ReCaptcha V2 Secret Key</b>
                <input type="text" name="recaptcha_v2_secret_key" option-group="captcha" value="<?php echo get_option('recaptcha_v2_secret_key', 'captcha'); ?>" class="mw-ui-field mw_option_field mw-full-width" />
            </div>

        </div>
    </div>
</div>
<?php else: ?>

Captcha can be configured from admin.

<?php endif; ?>
