<?php must_have_access(); ?>
<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<script>
    function edit_mail_template(template_id) {
        $('#list-mail-templates').slideUp();

        // append edit
        $('#list-mail-templates').after('<div type="admin/mail_templates/edit" data_template_id="' + template_id + '" id="edit-mail-template"></div>');
        mw.reload_module("#edit-mail-template");
    }
</script>

<?php
$mail_template_type = '';
if (isset($params['mail_template_type'])) {
    $mail_template_type = $params['mail_template_type'];
}
?>

<module type="admin/mail_templates/list" mail_template_type="<?php print $mail_template_type ?>" id="list-mail-templates"/>