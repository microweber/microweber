<?php //d($params); 
$orig_params = $params;
if(isset($params['id'])){
unset($params['id']);	
}
$opts = get_option($params);
//d($opts);
?>
<?php if(is_array($opts)): ?>
<?php foreach($opts as $params): ?>
<?php include('edit.php'); ?>
<?php endforeach; ?>
<?php  else: ?>
<?php _e("No options found"); ?>
<?php endif; ?>

 
 
 
 