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
        <h4 class="alert-heading">Warning!</h4>
        <p class="mb-0">This module work only with the SOAP API, not the new one that is using REST. </p>
    </div>


    <input type="hidden" name="mail_provider_name" value="flexmail"/>
    <?php foreach (get_flexmail_api_fields() as $field): ?>
        <div class="form-group">
            <label class="control-label"><?php echo $field['title']; ?></label>
            <input type="text" value="<?php echo $field['value']; ?>" name="<?php echo $field['name']; ?>" class="mw_option_field form-control">
        </div>
    <?php endforeach; ?>

    <div class="form-group">
        <button type="button" class="btn btn-primary mail-provider-test-api-flexmail"><i class="mdi mdi-flask"></i> Test Api</button>
        <button type="button" class="btn btn-primary mail-provider-sync-api-flexmail"><i class="mdi mdi-cloud-sync"></i> Sync Subscribers</button>
      <!--  <button type="button" class="btn btn-primary mail-provider-logs-api-flexmail"><i class="mdi mdi-note-text"></i> Logs</button>-->
    </div>
</form>