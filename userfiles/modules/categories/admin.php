<?php if(isset($params['live_edit_sidebar'])): ?>
<?php include_once($config['path_to_module'].'admin_live_edit.php'); ?>
<?php elseif(isset($params['backend'])): ?>
<?php include_once($config['path_to_module'].'admin_backend.php'); ?>
<?php else: ?>
<?php include_once($config['path_to_module'].'admin_live_edit.php'); ?>
<?php endif; ?>

