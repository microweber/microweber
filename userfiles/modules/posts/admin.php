<?php
return;
only_admin_access();

// legacy file, please do not use it
// currently used only for the old shop/products module and will be removed

$dir_name = normalize_path(modules_path()); ?>

<?php if (isset($params['live_edit_sidebar'])): ?>
    <?php include_once($dir_name . 'content' . DS . 'admin_live_edit_sidebar.php'); ?>
<?php elseif (isset($params['backend'])): ?>
    <?php include_once($dir_name . 'content' . DS. 'admin_backend.php'); ?>
<?php else: ?>
    <?php include_once($dir_name. 'content' . DS . 'admin_live_edit.php'); ?>
<?php endif; ?>
<?php


