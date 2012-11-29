<? //d($params); 
$orig_params = $params;
if(isset($params['id'])){
unset($params['id']);	
}
$opts = get_options($params);
//d($opts);
?>
<? if(is_arr($opts)): ?>
<? foreach($opts as $params): ?>
<? include( $config['path_to_module'].'edit.php'); ?>
<? endforeach; ?>
<?  else: ?>
<? _e("No options found"); ?>
<? endif; ?>

 
 
 
 