<?php must_have_access(); ?>
<script>

    function edit_campaign(id = false) {
    var data = {};
        data.id = id;
        let modal_title = 'New Campaign';
        if (id > 0) {
            modal_title = 'Edit Campaign';
        }
        edit_campaign_modal = mw.tools.open_module_modal('newsletter/edit_campaign', data, {
            overlay: true,
            width: 960,
            skin: 'simple',
            title: modal_title
        });
    }

    function delete_campaign(id) {
    var ask = confirm("Are you sure you want to delete this campaign?");
            if (ask == true) {
    var data = {};
            data.id = id;
            $.ajax({
            url: mw.settings.api_url + 'newsletter_delete_campaign',
                type: 'POST',
                data: data,
                success: function (result) {
                    mw.notification.success('Campaign deleted');
                    //reload the modules
                    mw.reload_module('newsletter/campaigns_list')
                    mw.reload_module_parent('newsletter')
                }
            });
    }

    return false;
    }
</script>
<div class="mb-4">
    <div class="alert alert-info">
        Add this to your cron jobs to process campaigns. Frequency must be every 30 minutes.
        <div class="mt-2">
            <code class="style:font-weight:bold;">
                php artisan newsletter:process-campaigns
            </code>
            or <a href="javascript:;" onclick='Livewire.emit("openModal", "admin-newsletter-process-campaigns-modal")' style="border:1px solid #4299e1; border-radius:4px;text-transform:uppercase;font-size:10px;padding:4px 9px;color: #4299e1;">run the process manually</a>
        </div>
    </div>
</div>


<div class="card mt-2">
    <div class="card-body">
        <module type="newsletter/campaigns_list"/>
    </div>
</div>
