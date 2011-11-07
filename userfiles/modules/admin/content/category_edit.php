<? if($params['id'] == false){

	$params['id'] =url_param('id');
}


 if($params['add_to_parent'] != false){

	$parent = explode(',',$params['add_to_parent']);
	$force_parent = $parent[0];
	 
}


 
$rand = rand();
 //p($parent);
?>
<? 

if($params['save']){
	$save = CI::model('taxonomy')->taxonomySave($params, $preserve_cache = false) ;
	
	
	?>
<script type="text/javascript">
	
	
	$(document).ready(function () {
								
								
				
					
					
	if(typeof update_category_list == 'function'){
		update_category_list();
	}						
								
    
 });






</script>
<?
	//print('Saved id-' .$save);
	return;
}



//p($params);
$cat = get_category(intval($params['id']));
//p($cat)

 $the_page =  CI::model('content')->contentsGetTheFirstBlogSectionForCategory($cat['id']);
//p($the_page);

if($force_parent != false){
	
	$cat['parent_id'] =$force_parent;
}


?>
<script type="text/javascript" language="javascript">
 

 
  
 

 


<? if(intval($the_page['id']) != 0): ?>
$(document).ready(function () {
$.ajax({
  type: 'POST',
  url: '<? print site_url('api/module'); ?>',
  data: ({module : 'admin/content/custom_fields_editor',page_id : <? print $the_page['id'] ?>, 'for' : 'category', category_id:"<? print intval($params['id']) ?>" }),
  success: function(resp3) {
	 // alert(resp2);
	  
	   $("#cat_custom_fields").html(resp3);
	   
	   
  } 
  
});
 });
<? endif; ?>		


 
  function save_the_category1($form){
	
$f = '#'+$form;
	 data1 = ($($f).serialize());
	 
	// data1=data1+'&module=admin/content/category_edit';
	//  data1=data1+'&page_id=<? print $params['page_id'] ?>';
	 //  data1=data1+'&save=true';
	 
	 
	
	 $.ajax({
  url: '<? print site_url('api/content/save_taxonomy'); ?>',
   type: "POST",
      data: (data1),
      dataType: "html",
      async:true,
	  success: function(resp) {
		  $("#save_category_resp").show();
		    $("#save_category").hide();
		   
		  if(typeof update_category_list == 'function'){
			update_category_list();
				}	
				
				
				
	mw.reload_module('admin/content/category_selector');
	
	 if(typeof set_categories == 'function'){
			 set_categories()
				}	
	
	
		
	  }
    });
	
 

}
 


$(document).ready(function() {


 
 // toggles the slickbox on clicking the noted link  
$('#category_edit_advanced_<? print intval($cat['id']) ?>_show').die('click');
  $('#category_edit_advanced_<? print intval($cat['id']) ?>_show').click(function() {
    $('#category_edit_advanced_<? print intval($cat['id']) ?>').toggle(400);
    return false;
  });
});


function toggle_change_par_c(){
	
	//$laout = load_layout_config_file(true)
	
  
$("#toggle_change_par_c").toggle();
 

  
}

</script>


 


<div id="save_category_resp" style="display:none"><h2>Category is saved.</h2></div>
<? if($cat['parent_id'] == false): ?>
<? $cat['parent_id'] =  $params['taxonomy_parent']; ?>
<? endif; ?>
<form id="save_category">
  <input name="taxonomy_type" type="hidden" value="category" />
  <label style="position: absolute;bottom:0;left:0">Id: <? print intval($cat['id']) ?></label>
  <input name="id" type="hidden" value="<? print intval($cat['id']) ?>" />
 
 <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td><label>Category Title:</label></td>
    <td><input class="field3" name="taxonomy_value" type="text" value="<? print $cat['taxonomy_value'] ?>" /></td>
  </tr>
  <tr>
    <td><label>Parent Category </label></td>
    <td><input class="field3" name="parent_id" id="cat_parent_id" type="hidden" value="<? print $cat['parent_id'] ?>" />
  
  <? 
  $c_parr  = false;
  if(intval($cat['parent_id']) != 0) {
	  
	$c_parr = get_category($cat['parent_id']);  
  }?>
  
  <? if($c_parr != false):?>
  <? $toggle_change_par_c_style = "display:none;" ?>
  <? print $c_parr['taxonomy_value'] ?> <a class="blue" href="javascript:toggle_change_par_c();"><small>(change)</small></a>
  <? else :?>
  
  <? endif ;?>
  
  <div class="drop drop_white" style="<?php print $toggle_change_par_c_style;  ?>" id="toggle_change_par_c"> <span class="drop_arr"></span> <span class="val">Choose Category</span>
    <div class="drop_list" style="display: none; top: 34px;height: auto">
      <microweber module="admin/content/category_selector" active_category="<? print $cat['parent_id'] ?>" update_field="#cat_parent_id"  />
    </div>
  </div></td>
  </tr>
  <tr>
    <td><a id="category_edit_advanced_<? print intval($cat['id']) ?>_show"><small>Advanced settings</small></a></td>
    <td>
  <div id="category_edit_advanced_<? print intval($cat['id']) ?>" style="display:none;">
    <?                if($params['quick_edit'] == false){             ?>
    <label>Content Type: </label>
    <input class="field3" name="content_type" type="text" value="<? print $cat['content_type'] ?>" />
    <label>Description:</label>
    <input class="field3" name="taxonomy_description" type="text" value="<? print $cat['taxonomy_description'] ?>" />
    <label>Full Description:</label>
    <input class="field3" name="content_body" type="text" value="<? print $cat['content_body'] ?>" />
    <label>Users can create subcategories:</label>
    <input class="field3" name="users_can_create_subcategories" type="text" value="<? print $cat['users_can_create_subcategories'] ?>" />
    <label>Users can create subcategories user level required:</label>
    <input class="field3" name="users_can_create_subcategories_user_level_required" type="text" value="<? print $cat['users_can_create_subcategories_user_level_required'] ?>" />
    <label>Users can create content:</label>
    <input class="field3" name="users_can_create_content" type="text" value="<? print $cat['users_can_create_content'] ?>" />
    <div id="cat_custom_fields"></div>
    <? } ?>
  </div></td>
  </tr>
  
   <tr>
    <td>&nbsp;</td>
    <td>  <a href="javascript:save_the_category1('save_category')" class="btn2 right">Save</a>
 </td>
  </tr>
</table>

  

 
</form>
