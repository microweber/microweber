<?php only_admin_access(); ?>

<script>

    function edit_subscriber(id = false) {
        var data = {};
        data.id = id;
        edit_subscriber_modal = mw.tools.open_module_modal('newsletter/edit_subscriber', data, {overlay: true, skin: 'simple'});
    }

    function delete_subscriber(id) {
        var ask = confirm("<?php _ejs('Are you sure you want to delete this subscriber?'); ?>");
        if (ask == true) {
            var data = {};
            data.id = id;
            $.ajax({
                url: mw.settings.api_url + 'newsletter_delete_subscriber',
                type: 'POST',
                data: data,
                success: function (result) {
                    mw.notification.success('<?php _ejs('Subscriber deleted'); ?>');

                    // Reload the modules
                    mw.reload_module('newsletter/subscribers_list')
                    mw.reload_module_parent('newsletter')
                }
            });
        }
        return false;
    }
</script>

<a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" onclick="edit_subscriber();" style="">
    <i class="fas fa-plus-circle"></i> &nbsp;
    <span><?php _e('Add new subscriber'); ?></span>
</a>


<div class="pull-right">
    <?php
    $subscribers_params = array();
    $subscribers_params['no_limit'] = true;
    $subscribers_params['order_by'] = "created_at desc";
    $subscribers = newsletter_get_subscribers($subscribers_params);
    ?>
    <?php if (is_array($subscribers)) : ?>
        <strong><?php print _e('Total'); ?>:</strong>
        <span><?php echo count($subscribers); ?> subscribers</span>
    <?php endif; ?>
</div>

<br/>
<br/>

<module type="newsletter/subscribers_list"/>
