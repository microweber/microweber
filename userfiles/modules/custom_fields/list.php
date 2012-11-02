<?

 $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }
 if(!isset($params['for_module_id'])){
	 if(isset($params['id'])){
		 $params['for_module_id'] = $params['id'];
	 }
	 
	 
 }
 
 /* if( $for  != 'module'){
	 if(isset($params['for_module_id'])){
	  $params['to_table_id'] = $params['for_module_id'];
	  unset($params['for_module_id']);
	 }
 }*/

  
 if(isset($params['for_module_id'])): ?>
	<?     
	// d($params);
	$more = get_custom_fields($for,$params['for_module_id'],1,false);    ?>
   
    <? if(!empty( $more)):  ?>
    <? foreach( $more as $field): ?>
     <?  print  make_field($field); ?>
    <?  print  make_field($field, false, 2); ?>
    <? endforeach; ?>
    <? endif; ?>
<? endif; ?>
