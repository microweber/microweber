<?php must_have_access(); ?>
<?php
if (!isset($params['option_group']) && empty($params['option_group'])) {
    echo 'Set option group!!';
    return;
}
$option_group = $params['option_group'];
$mail_template_type = $params['mail_template_type'];
?>

<script>
    function mw_admin_mail_templates_modal() {
        var modalTitle = '<?php _e('Manage Mail templates'); ?>';

        mw.dialog({
            content: '<div id="mw_admin_mail_templates_manage">Loading...</div>',
            title: modalTitle,
            width: 900,
            id: 'mw_admin_mail_templates_modal'
        });
    }

    function mw_admin_add_mail_template($mail_template_type) {
        mw_admin_mail_templates_modal();

        $('#mw_admin_mail_templates_manage').attr('mail_template_type', $mail_template_type);
        mw.load_module('admin/mail_templates/edit', '#mw_admin_mail_templates_manage', null, null);
    }

    function mw_admin_edit_mail_templates($mail_template_type) {
        mw_admin_mail_templates_modal();
        $('#mw_admin_mail_templates_manage').attr('mail_template_type', $mail_template_type);

        mw.load_module('admin/mail_templates/admin', '#mw_admin_mail_templates_manage', null, null);
    }
</script>

<div class="form-group my-3">
    <label class="form-label"><?php _e("Select email template"); ?></label>
    <small class="text-muted"><?php _e("Choose template to send for users"); ?></small>
        <button onclick="mw_admin_add_mail_template('<?php echo $mail_template_type; ?>')" class="btn btn-sm btn-link d-block my-2"><?php _e('Add new email template'); ?></button>
    <small class="text-muted d-block"><?php _e("If you add few emails for same functionality they will be showing in dropdown box"); ?>.</small>
</div>

<div class=" d-flex align-items-center">
    <div class="col-md-8">
        <select name="<?php echo $mail_template_type; ?>_mail_template" class="mw_option_field form-select form-select-lg" data-width="100%" data-option-group="<?php echo $option_group; ?>" option-group="<?php echo $option_group; ?>">
            <option><?php _e("Select template"); ?></option>
            <?php foreach (get_mail_templates_by_type($mail_template_type) as $template): ?>
                <option value="<?php echo $template['id']; ?>" <?php if (get_option($mail_template_type . '_mail_template', $option_group) == $template['id']): ?>selected="selected"<?php endif; ?>><?php echo $template['name']; ?></option>
            <?php endforeach; ?>
        <select>
    </div>

    <div class="col-md-4">
        <button onclick="mw_admin_edit_mail_templates('<?php echo $mail_template_type; ?>')" class="btn btn-outline-success ms-2" title="<?php _e('Edit Templates'); ?>"><?php _e('Edit Templates'); ?></button>
    </div>
</div>
