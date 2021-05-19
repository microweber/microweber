<?php must_have_access(); ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<?php
if (isset($params["live_edit"]) and $params["live_edit"]) {
    include 'admin_live_edit.php';
} else {
    include 'admin_backend.php';
}
?>
