<?php only_admin_access(); ?>
<?php
if(!isset($params['option_group']) && empty($params['option_group']))  {
	echo 'Set option group!!';
	return;
}
$option_group = $params['option_group'];
$mail_template_type = $params['mail_template_type'];
?>
<script>
	
mw_admin_mail_templates_modal_opened = null;

    function mw_admin_mail_templates_modal() {
        
		var modalTitle = '<?php _e('Manage Mail templates'); ?>';
    	
		mw_admin_mail_templates_modal_opened = mw.modal({
            content: '<div id="mw_admin_mail_templates_manage">Loading...</div>',
            title: modalTitle,
            width:900,
            id: 'mw_admin_mail_templates_modal'
        });
    }
    
    function mw_admin_add_mail_template($mail_template_type) {
    	mw_admin_mail_templates_modal();

        $('#mw_admin_mail_templates_manage').attr('mail_template_type',$mail_template_type);
    	mw.load_module('admin/mail_templates/edit', '#mw_admin_mail_templates_manage', null, null);
    }

    function mw_admin_edit_mail_templates($mail_template_type) {
    	mw_admin_mail_templates_modal();
        $('#mw_admin_mail_templates_manage').attr('mail_template_type',$mail_template_type);

        mw.load_module('admin/mail_templates/admin', '#mw_admin_mail_templates_manage', null, null);
    }
</script>

<div class="mw-flex-row">
	<div class="mw-flex-col-md-5">
	<select name="<?php echo $mail_template_type; ?>_mail_template" class="mw-ui-field mw_option_field" data-option-group="<?php echo $option_group; ?>" option-group="<?php echo $option_group; ?>" style="width:330px;">
		<option>Select...</option>
		<?php foreach(get_mail_templates_by_type($mail_template_type) as $template): ?>
		<option value="<?php echo $template['id']; ?>" <?php if(get_option($mail_template_type . '_template', 'comments') == $template['id']): ?>selected="selected"<?php endif; ?>><?php echo $template['name']; ?></option>
	  	<?php endforeach; ?>
	<select>
 	</div>
 	<div class="mw-flex-col-md-4">   
	<button onclick="mw_admin_add_mail_template('<?php echo $mail_template_type; ?>')" class="mw-ui-btn mw-ui-btn-success" style="width:100%;" title="<?php print _e('Add New Template'); ?>"><?php print _e('Add New Template'); ?></button>
	</div>
 	<div class="mw-flex-col-md-3">
	<button onclick="mw_admin_edit_mail_templates('<?php echo $mail_template_type; ?>')" class="mw-ui-btn mw-ui-btn-info" style="width:100%;" title="<?php print _e('Edit Templates'); ?>"><?php print _e('Edit Templates'); ?></button>
	</div>
</div>