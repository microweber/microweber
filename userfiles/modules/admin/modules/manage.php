<?php if(!is_admin()){error("must be admin");}; ?>
<?php

 //$rand = uniqid(); ?>
<?php $load_module = url_param('load_module');
 if($load_module == true): ?>
<?php
$mod = str_replace( '___',DS, $load_module);
$mod = load_module($mod, $attrs=array('view' => 'admin','backend' => 'true'));
print $mod ;
?>
<?php else: ?>
<?php
 
$mod_params = array();
$mod_params['ui']  = 'any';
//$mod_params['debug']  = 'any';
//
if(isset($params['reload_modules'])){
	$s = 'skip_cache=1';
	if(isset($params['cleanup_db'])){
		$s.= '&cleanup_db=1';
	}
	
	 $mods = modules_list($s); 
}
if(isset($params['category'])){
	
	 $mod_params['category'] = $params['category'];
}


if(isset($params['keyword'])){
	
	// $mod_params['keyword'] = $params['keyword'];
}
if(isset($params['search-keyword'])){
	
	  $mod_params['keyword'] = $params['search-keyword'];
}



if(isset($params['show-ui'])){
	if($params['show-ui'] == 'admin'){
		$mod_params['ui_admin']  = '1';
	} else if($params['show-ui'] == 'live_edit'){
		$mod_params['ui']  = '1';
	}

}

if(isset($params['installed'])){
	
	  $mod_params['installed'] = '[int]'.$params['installed'];
}
  
 //d($mod_params);
 if(isset($params['install_new'])){
	  $update_api = new \Mw\Update();

$params = array();
 
$result = $update_api -> call('get_modules', $params);
 
	 
	 
	 $mods = $result;  
	 
} else {
	 $mods = get_modules_from_db($mod_params); 
}

 // $upds = mw_check_for_module_update();
   $upds = false;
 
?>
<?php if(isset($mods) and is_array($mods) == true): ?>

<ul class="mw-modules-admin">

<?php if(is_array($upds) == true): ?>
<?php foreach($upds as  $upd_mod): ?>
<?php if(isset($upd_mod['module'])): ?>
<?php $item = module_info($upd_mod['module']); ?>
<?php if(isset($item['id'])): ?>
<li class="mw-admin-module-list-item mw-module-installed-<?php print $item['installed'] ?>" id="module-db-id-<?php print $item['id'] ?>" >
    <module type="admin/modules/edit_module" data-module-id="<?php print $item['id'] ?>" />
  </li>
    <?php endif; ?>
   <?php endif; ?>
 <?php endforeach; ?>
 <?php endif; ?>
  <?php foreach($mods as $k=>$item): ?>
  <?php if(!isset($item['id'])): ?>
  <li class="mw-admin-module-list-item mw-module-not-installed" id="module-remote-id-<?php print $item['id'] ?>" >
  
  <div class=" module module-admin-modules-edit-module ">
 <?php $data = $item; include($config["path"].'update_module.php'); ?>
</div>



   
  </li>
  <?php else : ?>
  <li class="mw-admin-module-list-item mw-module-installed-<?php print $item['installed'] ?>" id="module-db-id-<?php print $item['id'] ?>" >
    <module type="admin/modules/edit_module" data-module-id="<?php print $item['id'] ?>" />
  </li>
  <?php endif; ?>
  <?php endforeach; ?>
</ul>
<?php else : ?>
<?php _e("No modules found"); ?>.
<?php endif; ?>
<?php endif; ?>
