<?php only_admin_access(); ?>
<script>

    function start_campaign(id = false) {
        var data = {};
        data.id = id;
        start_campaign_modal = mw.tools.open_module_modal('newsletter/start_campaign', data, {overlay: true, skin: 'simple'});
    }

    function edit_campaign(id = false) {
        var data = {};
        data.id = id;
        edit_campaign_modal = mw.tools.open_module_modal('newsletter/edit_campaign', data, {overlay: true, skin: 'simple'});
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

<a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" onclick="edit_campaign(false);" style="">
    <i class="fas fa-plus-circle"></i> &nbsp;
    <span><?php _e('Add new campaign'); ?></span>
</a>

<div class="mw-clear"></div>

<br/>

<module type="newsletter/campaigns_list"/>
