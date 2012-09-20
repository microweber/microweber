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
 
 
// d($params);

 if($params['for_module_id']): ?>
	<?     
	 
	$more = get_custom_fields($for,$params['for_module_id'],1);    ?>
    <? if(!empty( $more)):  ?>
    <? foreach( $more as $field): ?>
    <?
 	 
	   print  make_field($field, false, 1); ?>
    <? endforeach; ?>
    <? endif; ?>
<? endif; ?>
