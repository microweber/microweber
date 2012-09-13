modules admin
<?
 
$mod_params = array();
//$mod_params['debug']  = 1;
if(isset($params['reload_modules'])){
	
	 $mods = get_elements('skip_cache=1'); 
	// d($mods);
}
if(isset($params['category'])){
	
	 $mod_params['category'] = $params['category'];
}


 $mods = get_elements_from_db($mod_params); 
 if( $mods == false){
	  $mods = get_elements('skip_cache=1'); 
	  $mods = get_elements_from_db($mod_params); 
 }
//

 
?>
<ul>
  <? if(!empty($mods)): foreach($mods as $k=>$item): ?>
  <li>
  <module type="admin/modules/edit_element" data-module-id="<? print $item['id'] ?>" />
    <? // d($item); ?>
  </li>
  <? endforeach; endif; ?>
</ul>
