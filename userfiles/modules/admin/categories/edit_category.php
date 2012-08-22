<? 
$form_rand_id  = uniqid();
if(!isset($params["data-category-id"])){
	$params["data-category-id"] = CATEGORY_ID;
}
 

$data = get_category_by_id($params["data-category-id"]); 

if($data == false or empty($data )){
include('_empty_category_data.php');	
}



 
?>

 <script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 $('#admin_edit_category_form_<? print $form_rand_id ?>').submit(function() { 

 
 mw.form.post($('#admin_edit_category_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_category') ?>', function(){
	 
	 
	 mw.reload_module('[data-type="categories"]');
	 });
 
  
//  var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');
 	 
		  // mw.reload_module($pmod);

 return false;
 
 
 });
   
 


 
   
});
</script>
<textarea>
 
 
 <? print_r($data); ?> 
 
 </textarea>
<form id="admin_edit_category_form_<? print $form_rand_id ?>">
  id
  <input name="id"  type="text" value="<? print ($data['id'])?>" />
  
    parent_id
  <input name="parent_id"  type="text" value="<? print ($data['parent_id'])?>" />
  
   to_table
  <input name="to_table"  type="text" value="<? print ($data['to_table'])?>" />
  
   to_table_id
  <input name="to_table_id"  type="text" value="<? print ($data['to_table_id'])?>" />
   
   taxonomy_type
  <input name="taxonomy_type"  type="text" value="<? print ($data['taxonomy_type'])?>" />
   
   taxonomy_value
  <input name="taxonomy_value"  type="text" value="<? print ($data['taxonomy_value'])?>" />
  
  
   taxonomy_description
  <input name="taxonomy_description"  type="text" value="<? print ($data['taxonomy_description'])?>" />
   
   
   content_body
  <input name="taxonomy_description"  type="text" value="<? print ($data['content_body'])?>" />
   
   
    
  
    position
  <input name="position"  type="text" value="<? print ($data['position'])?>" />
  
 
   <input type="submit" name="save" value="save" />
</form>


