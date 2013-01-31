<?  

 $skip_types = array();
 $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }
 
 
  if(isset($params['data-skip-type'])){
	$skip_types = explode(',',$params['data-skip-type']);
	$skip_types = array_trim($skip_types);
 
 }
 
 

  if(isset($params['content-id'])){
	$for_id = $params['content-id'];
	 $for = 'content';
 }   else {
 
 
 
 
  if(isset($params['for_id'])){
	$for_id = $params['for_id'];
 }  else if(isset($params['data-id'])){
		 $for_id = $params['data-id'];
	 } else  if(isset($params['id'])){
	$for_id = $params['id'];
 }
 
  //$for_id =$params['id'];
 if(isset($params['to_table_id'])){
$for_id =$params['to_table_id'];
 }
 }
 
 if(isset($params['content-id'])){
	$for_id = $params['content-id']; 
	 $for = 'table_content';
}


 $more = get_custom_fields($for ,$for_id,1); 
 
 ?>

<input type="hidden" name="for_id" value="<? print $for_id?>" />
<input type="hidden" name="for" value="<? print $for?>" />
<?

if(!empty($more )): ?>
<? $price_fields = array(); ?>
<? foreach($more  as $field): ?>
<? 
 if(!in_array($field['custom_field_type'],$skip_types)){
if(isset($field['custom_field_type'])  and $field['custom_field_type'] =='price'){
	$price_fields[] = $field;
} else {
print  make_field($field);  
}
 }
 ?>
<? endforeach; ?>
<? if(!in_array('price',$skip_types)  and isarr($price_fields )): ?>
<? $price_fields_c = count($price_fields); ?>
	<? if($price_fields_c >1) : ?>
    <select name="price">
      <? endif; ?>
      <? foreach($price_fields  as $field): ?>
      <? if($price_fields_c >1){ $field['make_select'] = true; } ?>
      <? print  make_field($field);   ?>
      <? endforeach; ?>
      <? if($price_fields_c >1) : ?>
    </select>
    <? endif; ?>
<? endif; ?>
<? else: ?>
<? mw_notif("You don't have any custom fields."); ?>
<? endif; ?>
