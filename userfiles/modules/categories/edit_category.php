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
	
	 
	 
	 mw.$('#admin_edit_category_form_<? print $form_rand_id ?>').submit(function() { 

 
 mw.form.post(mw.$('#admin_edit_category_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_category') ?>', function(){
	 
	 
	 mw.reload_module('[data-type="categories"]');
	  mw.reload_module('[data-type="pages_menu"]');
	 });

 return false;
 

 });
   
 


 
   
});
</script>


<form class="add-edit-page-post" id="admin_edit_category_form_<? print $form_rand_id ?>">

  <input name="id" type="hidden" value="<? print ($data['id'])?>" />










  <input name="to_table" type="hidden" value="<? print ($data['to_table'])?>" />
  <input name="to_table_id" type="hidden" value="<? print ($data['to_table_id'])?>" />
  <input name="data_type" type="hidden" value="<? print ($data['data_type'])?>" />


  <label class="mw-ui-label">Title</label>
  <input style="width: 660px;" class="mw-ui-field" name="title" type="text" value="<? print ($data['title'])?>" />




  <label class="mw-ui-label"><?php _e("Parent"); ?></label>

  <module style="width: 660px;" type="categories/dropdown" active-id="<? print ($data['parent_id'])?>" input-name="parent_id" />


  <label class="mw-ui-label"><?php _e("Description"); ?> </label>

  <textarea style="width: 660px;height: 50px;" class="mw-ui-field" name="description"><? print ($data['description'])?></textarea>


  <label class="mw-ui-label"><?php _e("Content"); ?></label>
  <textarea style="width: 660px;height: 100px;" class="mw-ui-field" name="content"><? print ($data['content'])?></textarea>



  <input name="position"  type="hidden" value="<? print ($data['position'])?>" />


  <div class="vSpace">&nbsp;</div>


  <input type="submit" class="mw-ui-btn" name="save" value="<?php _e("Save"); ?>" />

</form>
<microweber module="custom_fields" view="admin" for="categories" id="<? print ($data['id'])?>" />