modules admin
<?
 

if(isset($params['reload_modules'])){
	
	 $mods = modules_list('skip_cache=1'); 
}
 $mods = get_modules_from_db(); 
 if( $mods == false){
	  $mods = modules_list('skip_cache=1'); 
	  $mods = get_modules_from_db(); 
 }
//

 
?>
<ul>
  <? foreach($mods as $k=>$item): ?>
  <li>
  <module type="admin/modules/edit_module" data-module-id="<? print $item['id'] ?>" />
    <? // d($item); ?>
  </li>
  <? endforeach; ?>
</ul>
