<?
$id = $params['id'];

if(intval($id) != 0){
	$form_values = get_page($id);
	
	
	//p($form_values);
//$try_parent = url_param('content_parent');
if($try_parent != false){
	//$form_values['content_parent'] = $try_parent;
}
}

   
 // p($form_values);
 //   p($params);
   
?>


<h2 style="font-size: 20px;" class="blue_title"><? print $params['title'] ?></h2>
<input type="text" name="custom_field_<? print $params['name'] ?>"  class="custom_field_<? print $params['name'] ?> custom_field" value="<? print $form_values['custom_fields'][$params['name']]; ?>" />
