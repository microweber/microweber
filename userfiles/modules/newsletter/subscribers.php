<?php only_admin_access(); ?>

<script>mw.lib.require('font_awesome5');</script>

<script>
    function edit_subscriber(form) {
        var data = mw.serializeFields(form);
        $.ajax({
            url: mw.settings.api_url + 'newsletter_save_subscriber',
            type: 'POST',
            data: data,
            success: function (result) {
                mw.notification.success('<?php _e('Subscriber saved'); ?>');
                $('#add-subscriber-form').hide();
                $('#add-subscriber-form')[0].reset();

                //reload the modules
                mw.reload_module('newsletter/subscribers_list')
                mw.reload_module_parent('newsletter');
            }
        });
        return false;
    }
    function delete_subscriber(id) {
        var ask = confirm("<?php _e('Are you sure you want to delete this subscriber?'); ?>");
        if (ask == true) {
            var data = {};
            data.id = id;
            $.ajax({
                url: mw.settings.api_url + 'newsletter_delete_subscriber',
                type: 'POST',
                data: data,
                success: function (result) {
                    mw.notification.success('<?php _e('Subscriber deleted'); ?>');

                    //reload the modules
                    mw.reload_module('newsletter/subscribers_list')
                    mw.reload_module_parent('newsletter')
                }
            });
        }
        return false;
    }
</script>

<div class="mw-ui-field-holder add-new-button text-right">
    <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded m-b-10" href="javascript:;" onclick="$('.js-add-new-subscriber .mw-accordion-title').trigger('click');"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
</div>


<module type="newsletter/subscribers_list"/>


