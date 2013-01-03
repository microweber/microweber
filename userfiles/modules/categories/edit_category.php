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

</script>
<script  type="text/javascript">
 function set_category_parent_<? print $form_rand_id ?>(){
	 
	 $sel = mw.$('#edit_category_set_par_<? print $form_rand_id ?>').find(':selected').first();;
	 
	 
	 
	 is_cat = $sel.attr("data-category-id");
	  is_page = $sel.attr("data-page-id");
	   is_page = $sel.attr("data-page-id");  
	  
	 if(is_cat != undefined){
	 mw.$('#to_table_id_<? print $form_rand_id ?>').val(0);  

		 mw.$('#parent_id_<? print $form_rand_id ?>').val(is_cat);  
	 }
		  if(is_page != undefined){
		 mw.$('#to_table_id_<? print $form_rand_id ?>').val(is_page);  
		 
		  mw.$('#parent_id_<? print $form_rand_id ?>').val(0);
		 
		 
	 }
	 
	 
	 
	 
 
	 
 }


$(document).ready(function(){
	set_category_parent_<? print $form_rand_id ?>()
	 
	 
	 
	  mw.$('#admin_edit_category_form_<? print $form_rand_id ?> select').change(function() { 
	  set_category_parent_<? print $form_rand_id ?>();
	   });
	    
	 
	 
	 
	 
	 mw.$('#admin_edit_category_form_<? print $form_rand_id ?>').submit(function() { 

 set_category_parent_<? print $form_rand_id ?>();
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
  <input name="table" type="hidden" value="table_taxonomy" />
  <input name="to_table" type="hidden" value="<? print ($data['to_table'])?>" />
  <input name="to_table_id" type="hidden" value="<? print ($data['to_table_id'])?>" id="to_table_id_<? print $form_rand_id ?>"  />
  <input name="data_type" type="hidden" value="<? print ($data['data_type'])?>" />
  <? if(intval($data['id']) > 0): ?>
  <? $act = 'Edit ' ;?>
  <? else : ?>
  <? $act = 'Add new ' ;?>
  <? endif; ?>
  <label class="mw-ui-label"><? print $act ?> category title</label>
  <input style="width: 660px;" class="mw-ui-field" name="title" type="text" value="<? print ($data['title'])?>" />
  <label class="mw-ui-label">
    <?php _e("Parent"); ?>
  </label>
  <? // d($data); ?>
  <input name="parent_id" type="text" value="<? print ($data['parent_id'])?>" id="parent_id_<? print $form_rand_id ?>" />
  <div id="edit_category_set_par_<? print $form_rand_id ?>">
    <module style="width: 660px;" type="categories/dropdown"   active_ids="<? print ($data['parent_id'])?>" input-name="temp_<? print $form_rand_id ?>" />
  </div>
  <label class="mw-ui-label">
    <?php _e("Description"); ?>
  </label>
  <textarea style="width: 660px;height: 50px;" class="mw-ui-field" name="description"><? print ($data['description'])?></textarea>
  <label class="mw-ui-label">
    <?php _e("Content"); ?>
  </label>
  <textarea style="width: 660px;height: 100px;" class="mw-ui-field" name="content"><? print ($data['content'])?></textarea>
  <input name="position"  type="hidden" value="<? print ($data['position'])?>" />
  <div class="vSpace">&nbsp;</div>
  <input type="submit" class="mw-ui-btn" name="save" value="<?php _e("Save"); ?>" />
</form>
<microweber module="custom_fields" view="admin" for="categories" id="<? print ($data['id'])?>" />
