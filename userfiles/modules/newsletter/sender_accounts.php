<?php only_admin_access(); ?>
<script>

    function edit_sender(id = false) {
        var data = {};
        data.id = id;
        edit_campaign_modal = mw.tools.open_module_modal('newsletter/edit_sender', data, {overlay: true, skin: 'simple'});
    }

    function delete_sender(id) {
        var ask = confirm("Are you sure you want to delete this campaign?");
        if (ask == true) {
            var data = {};
            data.id = id;
            $.ajax({
                url: mw.settings.api_url + 'newsletter_delete_sender',
                type: 'POST',
                data: data,
                success: function (result) {

                    mw.notification.success('Sender deleted');

                    //reload the modules
                    mw.reload_module('newsletter/sender_accounts_list')
                    mw.reload_module_parent('newsletter')
                }
            });
        }


        return false;
    }
</script>

<a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" onclick="edit_sender(false);" style="">
    <i class="fas fa-plus-circle"></i> &nbsp;
    <span><?php _e('Add new sender'); ?></span>
</a>

<div class="mw-clear"></div>

<br/>

<module type="newsletter/sender_accounts_list"/>
