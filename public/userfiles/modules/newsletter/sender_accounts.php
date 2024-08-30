<?php must_have_access(); ?>

<script>
    function edit_sender(id = false) {
        var data = {};
        data.id = id;
        let modal_title = 'New sender';
        if (id > 0) {
            modal_title = 'Edit sender';
        }
        edit_campaign_modal = mw.tools.open_module_modal('newsletter/edit_sender', data, {
            overlay: true,
            skin: 'simple',
            title: modal_title
        });
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


<div class="card mt-2">
    <div class="card-body">
        <module type="newsletter/sender_accounts_list"/>
    </div>
</div>
