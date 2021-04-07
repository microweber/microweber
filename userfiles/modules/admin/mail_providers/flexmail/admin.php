<?php must_have_access(); ?>
<script>
    $(document).ready(function () {
        var mail_provider_settings_form_class = '.mail-provider-flexmail-settings-form';
        $(mail_provider_settings_form_class).on('change paste', 'input, select, textarea', function () {
            $.post(mw.settings.api_url + 'save_mail_provider', $(mail_provider_settings_form_class).serialize(), function (data) {
                mw.notification.success('Settings are saved.');
            });
        });

        $('.mail-provider-test-api-flexmail').click(function () {

            $.post(mw.settings.api_url + 'save_mail_provider', $(mail_provider_settings_form_class).serialize());

            mw.notification.warning('Testing...');
            $.post(mw.settings.api_url + 'test_mail_provider', $(mail_provider_settings_form_class).serialize(), function (data) {
                if (data === '1') {
                    mw.notification.success('Sucessfull connecting.');
                } else {
                    mw.notification.error('Wrong mail provider settings.');
                }
            });

        });
    });
</script>

<form class="mail-provider-flexmail-settings-form" method="post">


    <div class="alert alert-dismissible alert-warning">
        <h4 class="alert-heading"><?php _e("Warning"); ?>!</h4>
        <p class="mb-0"><?php _e("This module work only with the SOAP API, not the new one that is using REST."); ?> </p>
    </div>

    <?php
    if (!extension_loaded('soap')) {
        ?>
        <div class="alert alert-danger">
            <p class="mb-0"><?php _e("Contact your hosting provider to enable PHP SOAP extension."); ?> </p>
        </div>
    <?php
        return false;
    }
    ?>

    <input type="hidden" name="mail_provider_name" value="flexmail"/>
    <?php foreach (get_flexmail_api_fields() as $field): ?>
        <div class="form-group">
            <label class="control-label"><?php echo $field['title']; ?></label>
            <input type="text" value="<?php echo $field['value']; ?>" name="<?php echo $field['name']; ?>" class="mw_option_field form-control">
        </div>
    <?php endforeach; ?>


    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Enabled"); ?></label>
        <?php $is_active = get_option('active', 'flexmail_provider'); ?>

        <ul class="mw-ui-inline-list">
            <li>
                <label class="mw-ui-check">
                    <input class="mw_option_field" type="radio" name="active" <?php if ($is_active == 'y'): ?> checked <?php endif; ?> value="y" option-group="flexmail_provider">
                    <span></span><span><?php _e("Yes"); ?></span>
                </label>
            </li>
            <li>
                <label class="mw-ui-check">
                    <input class="mw_option_field" type="radio" name="active" <?php if (!$is_active or $is_active != 'y'): ?> checked <?php endif; ?> value="n" option-group="flexmail_provider">
                    <span></span><span><?php _e("No"); ?></span>
                </label>
            </li>
        </ul>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-primary mail-provider-test-api-flexmail"><i class="mdi mdi-flask"></i> <?php _e("Test Api"); ?></button>
      <!--  <button type="button" class="btn btn-primary mail-provider-logs-api-flexmail"><i class="mdi mdi-note-text"></i> Logs</button>-->
    </div>
</form>