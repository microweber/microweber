<h2>Custom fields</h2>
<? // p( $params); ?>

 <? 
 
 
 
 $fields = get_custom_fields_for_content(POST_ID);
 
 if(!empty($fields)){
	 
	 
	foreach($fields as $field){
		
		if($field['config'] != false){ ?>
			
			 
             
             
             <mw module="content/custom_field" name="<? print $field['custom_field_name']  ?>" value="<? print $field['custom_field_value']  ?>" />
             
             
             
	<?	}
		
		
		
		
		
		
	}
	 
	 
 }
 
 
 

 ?>