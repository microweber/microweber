<?php only_admin_access(); ?>

<script>

	function edit_subscriber(id = false) {
		var data = {};
        data.id = id;
		edit_subscriber_modal = mw.tools.open_module_modal('newsletter/edit_subscriber', data, {overlay: true, skin: 'simple'});
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

                    // Reload the modules
                    mw.reload_module('newsletter/subscribers_list')
                    mw.reload_module_parent('newsletter')
                }
            });
        }
        return false;
    }
</script>

<button class="mw-ui-btn mw-ui-btn-icon" onclick="edit_subscriber();"> 
	<span class="mw-icon-plus"></span> <?php _e('Add new subscriber'); ?>
</button>

<br />
<br />

<module type="newsletter/subscribers_list"/>
