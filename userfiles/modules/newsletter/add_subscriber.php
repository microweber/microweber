<?php only_admin_access(); ?>
<script>
    function edit_subscriber(form) {
        var data = mw.serializeFields(form);
        $.ajax({
            url: mw.settings.api_url + 'newsletter_save_subscriber',
            type: 'POST',
            data: data,
            success: function (result) {
                $('.js-subscribers-list .mw-accordion-title').trigger('click');
                mw.notification.success('<?php _e('Subscriber saved'); ?>');
                $('#add-subscriber-form')[0].reset();

                //reload the modules
                mw.reload_module('newsletter/subscribers_list')
                mw.reload_module('newsletter/add_subscriber')
                mw.reload_module_parent('newsletter');
            }
        });
        return false;
    }
</script>


<form id="add-subscriber-form" onSubmit="edit_subscriber(this); return false;">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Subscriber Name'); ?></label>
        <input name="name" type="text" class="mw-ui-field mw-full-width"/>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Subscriber Email'); ?></label>
        <input name="email" type="text" class="mw-ui-field mw-full-width"/>
    </div>

    <div class="mw-ui-field-holder text-right">
        <button type="submit" class="mw-ui-btn mw-ui-btn-info"><?php _e('Save'); ?></button>
    </div>
</form>



