<? if(!is_admin()){error("must be admin");}; ?>
<?

 //$rand = uniqid(); ?>
<? $load_module = url_param('load_module');
 if($load_module == true): ?>
<?
$mod = str_replace( '___',DS, $load_module);
$mod = load_module($mod, $attrs=array('view' => 'admin','backend' => 'true'));
print $mod ;
?>
<? else: ?>


<?
 
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
	 $update_api = new \mw\update();

$params = array();
$params['email'] = 'my@email';
$params['password'] = 'pass';
$result = $update_api -> call('get_modules', $params);
 
	 
	 
	 $mods = $result; 
	 
} else {
	 $mods = get_modules_from_db($mod_params); 
}


 if( $mods == false){
	 // $mods = modules_list('skip_cache=1'); 
	//  $mods = get_modules_from_db($mod_params); 
 }
?>
<? if(isarr($mods) == true): ?>
<ul class="mw-modules-admin">
  <? foreach($mods as $k=>$item): ?>
  
   <? if(!isset($item['installed'])): ?>
    <li class="mw-admin-module-list-item mw-module-not-installed" id="module-remote-id-<? print $item['id'] ?>" >
 
     <? $data = $item; include($config["path"].'update_module.php'); ?>
  </li>
   <? else : ?>
    <li class="mw-admin-module-list-item mw-module-installed-<? print $item['installed'] ?>" id="module-db-id-<? print $item['id'] ?>" >
 
    <module type="admin/modules/edit_module" data-module-id="<? print $item['id'] ?>" />
  </li>
   <? endif; ?>
 
  <? endforeach; ?>
</ul>
<? else : ?>
No modules found.
<? endif; ?>
<? endif; ?>
