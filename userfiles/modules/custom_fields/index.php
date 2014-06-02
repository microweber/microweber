<?php  

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
 } elseif(isset($params['content_id'])){
	$for_id = $params['content_id'];
	 $for = 'content';
 }    else {
 	 
	 if(isset($params['for_id'])){
		$for_id = $params['for_id'];
	 }  else if(isset($params['data-id'])){
			 $for_id = $params['data-id'];
	 } else  if(isset($params['id'])){
		$for_id = $params['id'];
	 }
	 
	  //$for_id =$params['id'];
	if(isset($params['rel_id'])){
	$for_id =$params['rel_id'];
	}
 }
 
if(((!isset($for_id)) and isset($params['data-id']))){
	$for_id = $params['data-id']; 
 
}
 
if(isset($params['default-fields']) and isset($params['parent-module-id'])){
	 
	mw()->fields->make_default($for,$for_id,$params['default-fields']);
}






 $more = mw()->fields->get($for ,$for_id,1); 
 $prined_items_count = 0;
 
 
 
 ?>

<input type="hidden" name="for_id" value="<?php print $for_id?>" />
<input type="hidden" name="for" value="<?php print $for?>" />
<?php if(!empty($more )): ?>
<?php $price_fields = array(); ?>
<?php foreach($more  as $field): ?>
<?php 
    if(!in_array($field['custom_field_type'],$skip_types)){
		if(isset($field['custom_field_type'])  and $field['custom_field_type'] =='price'){
			$price_fields[] = $field;
		} else {
			$prined_items_count++;
			$field['params'] = $params;
			print  mw()->fields->make($field);  
    	}
     }
     ?>
<?php endforeach; ?>
<?php if(!in_array('price',$skip_types)  and is_array($price_fields )): ?>
<?php $price_fields_c = count($price_fields); ?>
<?php if($price_fields_c >1) : ?>
<select name="price">
  <?php endif; ?>
  <?php foreach($price_fields  as $field): ?>
  <?php 
               $prined_items_count++;
               if($price_fields_c >1){ $field['make_select'] = true; } ?>
  <?php  print  mw()->fields->make($field);   ?>
  <?php endforeach; ?>
  <?php if($price_fields_c >1) : ?>
</select>
<?php  else: ?>
<?php endif; ?>
<?php endif; ?>
<?php else: ?>
<?php endif; ?>
<?php if($prined_items_count == 0): ?>
<?php print lnotif("Click on settings to edit your custom fields."); ?>
<?php endif; ?>
