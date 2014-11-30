elements admin
<?php
 
$mod_params = array();
 //$mod_params['debug']  = 1;
if(isset($params['reload_modules'])){
	$s = 'skip_cache=1';
	if(isset($params['cleanup_db'])){
		$s.= '&cleanup_db=1';
		$mod_params['cleanup_db'] = 1;
		$mod_params['skip_cache'] = 1;
		
		 $mods = mw()->modules->get_layouts($mod_params); 
		// d($mods);
	}
	
	 
	 //$mods = get_elements($s); 
}
if(isset($params['category'])){
	
	 $mod_params['category'] = $params['category'];
}


 $mods = mw()->layouts_manager->get($mod_params); 
 if( $mods == false){
	  $mods = get_elements('skip_cache=1'); 
	  $mods = mw()->layouts_manager->get($mod_params); 
 }
  //d( $params );
 
//

 
?>
<ul>
  <?php if(!empty($mods)): foreach($mods as $k=>$item): ?>
  <li>      
  <module type="admin/modules_manager/edit_element" data-module-id="<?php print $item['id'] ?>" />
    <?php // d($item); ?>
  </li>
  <?php endforeach; endif; ?>
</ul>
