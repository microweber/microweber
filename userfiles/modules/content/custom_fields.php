<? if(POST_ID): ?>
<editable  post="<? print PAGE_ID ?>" field="custom_field_product_inner_custom_fields_title">
<h4>Item properties</h4></editable>
<?  // p( $params); ?>
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
<? endif; ?>
