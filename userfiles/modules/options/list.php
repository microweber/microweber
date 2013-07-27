<?php //d($params); 
$orig_params = $params;
if(isset($params['id'])){
unset($params['id']);	
}
$opts = mw('option')->get($params);
//d($opts);
?>
<?php if(is_arr($opts)): ?>
<?php foreach($opts as $params): ?>
<?php include('edit.php'); ?>
<?php endforeach; ?>
<?php  else: ?>
<?php _e("No options found"); ?>
<?php endif; ?>

 
 
 
 