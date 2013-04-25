<? if(!isset($params['live_edit'])): ?>
<? include_once($config['path_to_module'].'admin_backend.php'); ?>
<? else: ?>
<? include_once($config['path_to_module'].'admin_live_edit.php'); ?>
<? endif; ?> 