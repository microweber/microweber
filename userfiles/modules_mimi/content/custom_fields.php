<?

if($content_id){
	
	$the_post_id = $content_id;
} else {
	
	$the_post_id = $the_post_id;
}


if($the_post_id): ?>
<?  

if($params['only_type'] != false){
$only_type = 	trim($params['only_type']);
	
} else {
$only_type = 	false;
}


if($params['only_value'] != false){
$only_value = 	trim($params['only_value']);
	
} else {
$only_value = 	false;
}

//p( $params); ?>
<? 
 $fields = get_custom_fields_for_content($the_post_id);
 //$fields2 = get_custom_fields_for_content(PAGE_ID);
 // $fields3 =get_custom_fields_config_for_content($the_post_id);
 
  $fields3 = get_custom_fields_config_for_content($the_post_id);
 //   p($fields3);
 
 //$fields = array_merge($fields,$fields2,  $fields3);
  //p($fields3);
 if(!empty($fields3)){
		 $fields =  $fields3;
		   }
  //p($fields3);
 if(!empty($fields)){
	 
	 ?>
<? if($only_value == ''): ?>

<div class="custom_fields_holder">
  <? endif; ?>
  <?
	 
	foreach($fields as $field){
		 //p($field);
		 
		 if($only_type != false){
			 
			  if($field['type'] == $only_type ){
				  $yes = 1; 
				  
			  } else {
				  
				$yes = 0;   
			  }
			 
			 
		 } else {
			$yes = 1; 
		 }
		 
		 
		?>
  <? if($yes == 1): ?>
  <? if($only_value == ''): ?>
  <div class="custom_field">
  
  
  
  
  
    <? endif; ?>
    
    
    
    
    
    
    
    
    
    
 
    
    
    
    
    
    <?
		//if($field['config'] != false){ ?>
    <? /*<mw module="content/custom_field" name="<? print $field['custom_field_name']  ?>" value="<? print $field['custom_field_value']  ?>" />*/ ?>
    <?  if(intval($the_post_id) > 0 ): ?>
    <?
	
	
	if(($field["posr_id"] != 'posdasdasdt')) :
	
	
 // p($field);
	?>
    <microweber module="content/custom_field" cf_id="<? print $field['id'] ?>"  only_value="<? print $only_value ?>"  module_id="custom_field_<? print PAGE_ID ?><? print $the_post_id ?><? print $field['id'] ?>" cf_type="<? print $field['type'] ?>" />
    <? endif; ?>
    <? else: ?>
    <microweber module="content/custom_field" cf_id="<? print $field['id'] ?>"  only_value="<? print $only_value ?>"   module_id="custom_field_<? print PAGE_ID ?><? print $the_post_id ?><? print $field['id'] ?>" cf_type="<? print $field['type'] ?>" />
    <? endif; ?>
    <?	//}
	?>
    <? if($only_value == ''): ?>
  </div>
  <? endif; ?>
  <? endif; ?>
  <?
  
  
  
  
  
	}
?>
  <? if($only_value == ''): ?>
</div>
<? endif; ?>
<?
 }
 
 
 

 ?>
<? endif; ?>
