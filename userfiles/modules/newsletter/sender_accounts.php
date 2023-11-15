<?php must_have_access(); ?>

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

<a href="javascript:;" class="btn btn-primary mb-3" onclick="edit_sender(false);"><i class="mdi mdi-plus"></i> <?php _e('Add new sender'); ?></a>

<module type="newsletter/sender_accounts_list"/>
