<?php only_admin_access(); ?>


<div class="module-live-edit-settings module-ants-settings">
    <div class="mw-ui-field-holder">
        <b><?php _e("Captcha provider"); ?></b>

        <select class="mw-ui-field mw_option_field mw-full-width" name="captcha_provider">
            <option value="microweber">Select</option>
            <option value="google_recaptcha"  <?php if(get_option('captcha_provider', $params['id']) == 'google_recaptcha'): ?>selected="selected"<?php endif; ?>>Google ReCaptcha</option>
            <option value="microweber" <?php if(get_option('captcha_provider', $params['id']) == 'microweber'): ?>selected="selected"<?php endif; ?>>Microweber Captcha</option>
        </select>


    </div>
</div>
