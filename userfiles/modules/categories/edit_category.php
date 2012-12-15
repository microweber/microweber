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
	 
	 $sel = mw.$('#edit_category_set_par_<? print $form_rand_id ?> input:checked').parents('li').first();

	 
	
	 is_cat = $sel.attr("data-category-id");
	  is_page = $sel.attr("data-page-id");
	//   is_page = $sel.attr("data-page-id");  
	    mw.log( $sel);
	 if(is_cat != undefined){
	 mw.$('#to_table_id_<? print $form_rand_id ?>').val(0);

		 mw.$('#parent_id_<? print $form_rand_id ?>').val(is_cat);  
		 
		 
		
	 }
		 
		 
		 
		 
		 
		  if(is_page != undefined){
		 mw.$('#to_table_id_<? print $form_rand_id ?>').val(is_page);  
		 
		  mw.$('#parent_id_<? print $form_rand_id ?>').val(0);
		 
		 
	 }
	 
	 
	 
	 
 
	 
 }
 

  function onload_set_parent_<? print $form_rand_id ?>(){
	   var tti = mw.$('#to_table_id_<? print $form_rand_id ?>').val();

		 var par_cat   = mw.$('#parent_id_<? print $form_rand_id ?>').val();
		// mw.log(par_cat);
		 if(par_cat != undefined && parseFloat(par_cat) > 0 ){
		    var tree =  mwd.getElementById('edit_category_set_par_<? print $form_rand_id ?>');
            var li =  tree.querySelector('li[data-category-id="'+par_cat+'"]');
            var radio = li.querySelector('input[type="radio"]');
            radio.checked = true;

		 }  else  if(tti != undefined && parseFloat(tti) > 0 ){
                   var tree =  mwd.getElementById('edit_category_set_par_<? print $form_rand_id ?>');
            var li =  tree.querySelector('li[data-page-id="'+tti+'"]');
            var radio = li.querySelector('input[type="radio"]');
            radio.checked = true;

                }

  }


$(document).ready(function(){
	//
	 <? if(intval($data['id']) == 0): ?>
	// onload_set_parent_<? print $form_rand_id ?>();
	// set_category_parent_<? print $form_rand_id ?>()
	 <? endif; ?>
	  mw.$('#edit_category_set_par_<? print $form_rand_id ?> input').change(function() { 
	//  alert(1);
	     set_category_parent_<? print $form_rand_id ?>();
	   });
	    
	 
	 
	 
	 
	 mw.$('#admin_edit_category_form_<? print $form_rand_id ?>').submit(function() { 

 // set_category_parent_<? print $form_rand_id ?>();
 mw.form.post(mw.$('#admin_edit_category_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_category') ?>', function(){
	 
	 
	 mw.reload_module('[data-type="categories"]');
	  mw.reload_module('[data-type="pages_menu"]');
	  <? if(intval($data['id']) == 0): ?>
	 	mw.url.windowHashParam("action", "editcategory:" + this);
<? endif; ?>
	  
	  
	  
	 });

 return false;
 

 });
   
 


 
   
});
</script>
<? if(intval($data['id']) == 0){
	  if(isset($params['selected-category-id']) and intval($params['selected-category-id']) != 0){
		  $data['parent_id'] = intval($params['selected-category-id']);
	  } elseif(isset($params['page-id'])){
		  $data['to_table_id'] = intval($params['page-id']);
	  }
	  
  }

  ?>
<?  //d($params);?>

<form class="add-edit-page-post" id="admin_edit_category_form_<? print $form_rand_id ?>" name="admin_edit_category_form_<? print $form_rand_id ?>" autocomplete="Off">
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
  <div class="mw-ui-field-holder">
    <div class="post-save-top"> <a href="javascript:;" style="min-width: 66px;" onclick="$(document.forms['admin_edit_category_form_<? print $form_rand_id ?>']).submit();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green">
      <?php _e("Save"); ?>
      </a> </div>
    <label class="mw-ui-label" style="padding-top: 5px;"><? print $act ?> category</label>
    <input style="width: 600px;" class="mw-ui-field" name="title" type="text" value="<? print ($data['title'])?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Parent"); ?>
    </label>
    <?
      $is_shop = '';
    if (isset($params['is_shop'])) {
    	//$is_shop = '&is_shop=' . $params['is_shop'];
    }
	
	 
	 
       ?>
    <input name="parent_id" type="hidden" value="<? print ($data['parent_id'])?>" id="parent_id_<? print $form_rand_id ?>" />
    <div class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector" id="edit_category_set_par_<? print $form_rand_id ?>">
      <module  type="categories/selector"   categories_active_ids="<? print (intval($data['parent_id']))?>" active_ids="<? print ($data['to_table_id'])?>" <? print $is_shop ?> input-name="temp_<? print $form_rand_id ?>" input-name-categories='temp_<? print $form_rand_id ?>' input-type-categories="radio"   />
    </div>
  </div>
  <script type="text/javascript">
    $(mwd).ready(function(){
        mw.treeRenderer.appendUI('#edit_category_set_par_<? print $form_rand_id ?>');
        mw.tools.tree.openAll(mwd.getElementById('edit_category_set_par_<? print $form_rand_id ?>'));
    });
  </script>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Description"); ?>
    </label>
    <textarea style="width: 600px;height: 50px;" class="mw-ui-field" name="description"><? print ($data['description'])?></textarea>
  </div>
  <input name="position"  type="hidden" value="<? print ($data['position'])?>" />
  <input type="submit" class="semi hidden" name="save" />
</form>
<microweber module="custom_fields" view="admin" for="categories" id="<? print ($data['id'])?>" />
<div class="post-save-bottom">
  <input type="submit" name="save" class="semi_hidden"  value="Save" />
  <a href="javascript:;" style="min-width: 66px;" onclick="$(document.forms['admin_edit_category_form_<? print $form_rand_id ?>']).submit();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green">
  <?php _e("Save"); ?>
  </a> </div>
