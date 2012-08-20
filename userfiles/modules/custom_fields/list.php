<?
 if(!isset($params['for_module_id'])){
	 if(isset($params['id'])){
		 $params['for_module_id'] = $params['id'];
	 }
	 
	 
 }

 if($params['for_module_id']): ?>
	<?     $more = get_custom_fields('module',$params['for_module_id'],1);    ?>
    <? if(!empty( $more)):  ?>
    <? foreach( $more as $field): ?>
    <?
//	d($field);
	   print  make_field($field, false, 1); ?>
    <? endforeach; ?>
    <? endif; ?>
<? endif; ?>
