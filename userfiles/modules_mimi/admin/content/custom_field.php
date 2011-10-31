<?
//p($params);

?>
<? if($params['element_id']): ?>
<script type="text/javascript">
function edit_all_custom_fields<? print $params['element_id'] ?>(){
	
	
	if(typeof window.load_edit_module_by_module_id == 'function') {
	 $cf_module_by_id  =$("div[module_id='<? print $params['module_id'] ?>']"); 
	 
	   if($cf_module_by_id != undefined){
				$cf_all =	$cf_module_by_id.parents('div[mw_params_module="content/custom_fields"]').attr('module_id');
				
				
 load_edit_module_by_module_id($cf_all) 




				
				
				
				
			//	alert($cf_all);
		  }
		  
		  }
		  
	
	//alert('<? print $params['element_id'] ?>');
	
	
	
	
}
</script>

<a href="javascript:edit_all_custom_fields<? print $params['element_id'] ?>()">Edit all fields</a>
<? endif;  ?>
<?
//p($config);
include($config['path_to_module'].'custom_fields_editor.php');

?>
