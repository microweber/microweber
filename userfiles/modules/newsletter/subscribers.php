<?php only_admin_access(); ?>
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

<a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="$('#add-subscriber-form').show()"> <span
            class="mw-icon-plus"><?php _e('Add new subscriber'); ?></span> </a>
<form id="add-subscriber-form" onSubmit="edit_subscriber(this); return false;" style="display:none">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Subscriber Name'); ?></label>
        <input name="name" type="text" class="mw-ui-field"/>
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Subscriber Email'); ?></label>
        <input name="email" type="text" class="mw-ui-field"/>
    </div>
    <button type="submit" class="mw-ui-btn"><?php _e('Save'); ?></button>
</form>
<div class="mw-clear"></div>
<br/>
<module type="newsletter/subscribers_list"/>
