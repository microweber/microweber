<?php only_admin_access(); ?>

<script>
	function list_templates() {
		$('.mw-iframe-editor').remove();
		$('.js-edit-template-wrapper').slideUp();
		$('.js-templates-list-wrapper').slideDown();
	}
	
	function edit_template(id = false) {
		
		var data = {};
		data.id = id;

		mw.notification.success('<?php _e('Loading...'); ?>');

		if (data.id > 0) {
    		 $.ajax({
                 url: mw.settings.api_url + 'newsletter_get_template',
                 type: 'POST',
                 data: data,
                 success: function (result) {
    
                	 $('.js-edit-template-id').val(result.id);
                     $('.js-edit-template-title').val(result.title);
                     $('.js-edit-template-text').val(result.text);
                     
					 initEditor();
                 }
             });
		} else {
			 $('.js-edit-template-id').val('0');
             $('.js-edit-template-title').val('');
             $('.js-edit-template-text').val('');
             
			 initEditor();
		}

		 $('.js-templates-list-wrapper').slideUp();
 		 $('.js-edit-template-wrapper').slideDown();
	}
    
    function delete_template(id) {
        var ask = confirm("<?php _e('Are you sure you want to delete this template?'); ?>");
        if (ask == true) {
            var data = {};
            data.id = id;
            $.ajax({
                url: mw.settings.api_url + 'newsletter_delete_template',
                type: 'POST',
                data: data,
                success: function (result) {
                    mw.notification.success('<?php _e('Template deleted'); ?>');

					// Back to old templates
                    list_templates();

                    $(".js-edit-template-form")[0].reset();
                    
                    // Reload the modules
                    mw.reload_module('newsletter/templates_list')
                    mw.reload_module_parent('newsletter')

                }
            });
        }
        return false;
    }
</script>

<button class="mw-ui-btn mw-ui-btn-icon" onclick="list_templates();"> 
	<?php _e('List of templates'); ?>
</button>

<button class="mw-ui-btn mw-ui-btn-icon" onclick="edit_template();"> 
	<span class="mw-icon-plus"></span> <?php _e('Add new template'); ?>
</button>

<br />
<br />

<div class="js-templates-list-wrapper">
<module type="newsletter/templates_list" />
</div>

<div class="js-edit-template-wrapper" style="display:none;">
<module type="newsletter/edit_template" />
</div>