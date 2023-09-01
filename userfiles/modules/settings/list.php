<div class="mw-settings-list<?php if(isset($params['option_group'])): ?> mw-settings-list-<?php print strtolower(trim($params['option_group'])) ?><?php endif; ?>">
	<?php
	$orig_params = $params;
	if(isset($params['id'])){
		unset($params['id']);
	}
	$opts = false;
	$is_system = '';
	if(isset($orig_params['is_system'])){
		$is_system = '&is_system='.intval($orig_params['is_system']);
	}
	if(isset($orig_params['for_module_id'])){

		$chck =   get_option('module=' . $orig_params['for_module_id']);
		if (isset($chck[0]) and isset($chck[0]['id'])) {
			$opts = $chck;

		}

	} else if(isset($orig_params['option_group'])){
		$chck =   get_option('option_group=' . $orig_params['option_group'].$is_system);
		if (isset($chck[0]) and isset($chck[0]['id'])) {
			$opts = $chck;

		}

	}
	if($opts == false and isset($params['for_module'])){
		$params['module'] = $params['for_module'];
//$opts = get_option('module=' . $params['for_module']);
	} else {
//	$opts = get_option($params);
	}


	if($opts == false){
 //	$opts = get_option($params);
	}



	?>
	<?php if(is_array($opts)): ?>

	<script type="text/javascript">
	//mw.require("options.js", true);
	mw.require("<?php print $config['url_to_module']; ?>settings.css");
	</script>
	<?php foreach($opts as $params): ?>
	<?php include( $config['path_to_module'].'edit.php'); ?>
<?php endforeach; ?>
<?php  else: ?>
<?php // _e("No options found"); ?>
<?php endif; ?>
</div>
