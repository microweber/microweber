<?php must_have_access(); ?>
<script>

    $(document).ready(function () {

        function getSerializeMailerliteForm()
        {
            return $('.mail-provider-mailerlite-settings-form').find('input, select, textarea').serialize();
        }

        $('.mail-provider-mailerlite-settings-form').on('change paste', 'input, select, textarea', function () {
            $.post(mw.settings.api_url + 'save_mail_provider', getSerializeMailerliteForm(), function () {
                mw.notification.success('Settings are saved.');
            });
        });

        $('.mail-provider-test-api-mailerlite').click(function () {

            $.post(mw.settings.api_url + 'save_mail_provider', getSerializeMailerliteForm());

            mw.notification.warning('Testing...');
            $.post(mw.settings.api_url + 'test_mail_provider', getSerializeMailerliteForm(), function (data) {
                if (data === '1') {
                    mw.notification.success('Sucessfull connecting.');
                } else {
                    mw.notification.error('Wrong mail provider settings.');
                }
            });

        });

    });
</script>


<div class="mail-provider-mailerlite-settings-form">
    <input type="hidden" name="mail_provider_name" value="mailerlite" />
    <?php foreach (get_mailerlite_api_fields() as $field): ?>
        <div class="form-group">
            <label class="form-label"><?php echo $field['title']; ?></label>
            <input type="text" value="<?php echo $field['value']; ?>" name="<?php echo $field['name']; ?>" class="mw_option_field form-control">
        </div>
    <?php endforeach; ?>

    <div class="mw-ui-field-holder">
        <div class="mw-ui-field-holder">
            <label class="form-label"><?php _e("Is enabled"); ?>?</label>
            <?php $is_active = get_option('active', 'mailerlite_provider'); ?>
            
            <label class="form-check form-check-single form-switch ps-0">
                <input class="form-check-input mw_option_field" type="checkbox" name="active" data-value-checked="y" data-value-unchecked="n" <?php if ($is_active == 'y'): ?> checked <?php endif; ?> value="y" value="n" option-group="mailerlite_provider">
            </label>
        </div>
    </div>

    <div class="form-group mt-3">
        <button type="button" class="btn btn-primary mail-provider-test-api-mailerlite"><?php _e("Test Api"); ?></button>
       <!-- <button type="button" class="btn btn-primary mail-provider-logs-api-mailerlite"><i class="mdi mdi-note-text"></i> Logs</button>-->
    </div>
</div>
