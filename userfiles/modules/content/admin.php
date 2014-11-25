<?php if(isset($params['backend'])): ?>
<?php include_once($config['path_to_module'].'backend.php'); ?>

<?php else: ?>
<?php 
$params['global'] = 1;


include_once($config['path_to_module'].'../posts/admin_live_edit.php'); ?>


<?php endif; ?>
 
 