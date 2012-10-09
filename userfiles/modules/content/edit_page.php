<?
if(!isset($edit_post_mode)){
	$edit_post_mode = false;
}

 

if(!isset($params["data-page-id"])){
	$params["data-page-id"] = PAGE_ID;
}
 
if(isset($params["data-content-id"])){
	$params["data-page-id"] = $params["data-content-id"];
}

if(isset($params["data-content"])){
	$params["data-page-id"] = $params["data-content"];
}
 //d($params);

$data = get_content_by_id($params["data-page-id"]); 
 
if($data == false or empty($data )){
include('_empty_content_data.php');	
}


if(isset($params["data-is-shop"])){
	$data["is_shop"] = $params["data-is-shop"];
}




$form_rand_id = $rand = uniqid();
?>
<script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 $('#admin_edit_page_form_<? print $form_rand_id ?>').submit(function() { 

 mw_before_content_save<? print $rand ?>()
 mw.form.post($('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
	 
	 mw_after_content_save<? print $rand ?>();
	 
	 });
 
  
//  var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');
 	 
		  // mw.reload_module($pmod);

 return false;
 
 
 });
   
   
    $('#go_live_edit_<? print $rand ?>').click(function() { 
mw_before_content_save<? print $rand ?>()
 
 mw.form.post($('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
	 
	 
	 
	 
  mw_after_content_save<? print $rand ?>(this);
	 
	 
	 
	 
	 
	 
	 });
	 
	 
	

 return false;
 
 
 });
 
 
 function mw_before_content_save<? print $rand ?>(){
	$('#admin_edit_page_form_<? print $form_rand_id ?> .module[data-type="custom_fields"]').empty();
 }
 
 function mw_after_content_save<? print $rand ?>($id){
	
	mw.reload_module('[data-type="pages_menu"]');
	  <? if($edit_post_mode != false): ?>
mw.reload_module('[data-type="posts"]');
	<? endif; ?>
	
	
	
	mw.reload_module('#admin_edit_page_form_<? print $form_rand_id ?> .module[data-type="custom_fields"]');
	if($id != undefined){
				$id = $id.replace(/"/gi, "");
				$.get('<? print site_url('api_html/content_link/') ?>'+$id, function(data) {
					//console.log(data);
			   window.location.href = data+'/editmode:y';
			  
			}); 
	
	}
	
	 
 }
   
   
 


 
   
});
</script>

<textarea>
 
 
 <? print_r($data); ?> 
 
 </textarea>
<form id="admin_edit_page_form_<? print $form_rand_id ?>">
  id
  <input name="id"  type="text" value="<? print ($data['id'])?>" />
  <br />
  parent
  <? if($edit_post_mode != false): ?>
  <?
  
  if(!isset($params["subtype"])){
	  if(intval($data['id']) != 0){
		  if(isset($data["subtype"]) and trim($data["subtype"]) != ''){
			  $params['subtype'] = $data["subtype"];
		  } else {
			  $params['subtype'] = 'post';
		  }
	  } else {
		  $params['subtype'] = 'post';
	  }
	
}

 ?>
  <? if(isset($params['subtype']) and $params['subtype'] == 'product'): ?>
  <? $pages = get_content('content_type=page&subtype=dynamic&is_shop=y&limit=1000');   ?>
  <? else: ?>
  <? $pages = get_content('content_type=page&subtype=dynamic&is_shop=n&limit=1000');   ?>
  <? endif; ?>
  <? if(!isset($params['subtype'])): ?>
  <?   $params['subtype'] = 'post'; ?>
  <? endif; ?>
  <br />
  subtype
  <input name="subtype"   value="<? print $params['subtype'] ?>" >
  <? if(!empty($pages)): ?>
  <select name="parent" id="the_post_parent_page<? print $rand ?>">
    <option value="0"   <? if((0 == intval($data['parent']))): ?>   selected="selected"  <? endif; ?>>None</option>
    <? if((0 != intval($data['parent']))): ?>
    <option value="<? print $data['parent'] ?>"     selected="selected"  ><? print $data['parent'] ?></option>
    <? endif; ?>
    <?
	$include_categories_in_cat_selector = array();
	 foreach($pages as $item):
	
	$include_categories_in_cat_selector[] = $item['subtype_value'];
	 ?>
    <option value="<? print $item['id'] ?>"   <? if(($item['id'] == $data['parent']) and $item['id'] != $data['id']): ?>   selected="selected"  <? endif; ?>  <? if($item['id'] == $data['id']): ?>    disabled="disabled"  <? endif; ?>  >
    <? print $item['title'] ?>
    </option>
    <? endforeach; ?>
  </select>
  <? endif; ?>
  <? else: ?>
  <? $pages = get_content('content_type=page&limit=1000');   ?>
  <? if(!empty($pages)): ?>
  <select name="parent">
    <option value="0"   <? if((0 == intval($data['parent']))): ?>   selected="selected"  <? endif; ?>>None</option>
    <? if((0 != intval($data['parent']))): ?>
    <option value="<? print $data['parent'] ?>"     selected="selected"  ><? print $data['parent'] ?></option>
    <? endif; ?>
    <? foreach($pages as $item): ?>
    <option value="<? print $item['id'] ?>"   <? if(($item['id'] == $data['parent']) and $item['id'] != $data['id']): ?>   selected="selected"  <? endif; ?>  <? if($item['id'] == $data['id']): ?>    disabled="disabled"  <? endif; ?>  >
    <? print $item['title'] ?>
    </option>
    <? endforeach; ?>
  </select>
  <? endif; ?>
  <? endif; ?>
  <br />
  <br />
  title
  <input name="title"  type="text" value="<? print ($data['title'])?>" />
  <br />
  url
  <input name="url"  type="text" value="<? print ($data['url'])?>" />
  <br />
  is_active
  <input name="is_active"  type="text" value="<? print ($data['is_active'])?>" />
  content_type
  <? if($edit_post_mode != false): ?>
  <? $data['content_type'] = 'post'; ?>
  <? endif; ?>
  <select name="content_type">
    <option value="page"   <? if(('page' == trim($data['content_type']))): ?>   selected="selected"  <? endif; ?>>page</option>
    <option value="post"   <? if(('post' == trim($data['content_type']))): ?>   selected="selected"  <? endif; ?>>post</option>
  </select>
  <? //d($edit_post_mode); ?>
  <? if($edit_post_mode != false): ?>
  <script  type="text/javascript">

 
 

$(document).ready(function(){
	
	 mw_load_post_cutom_fields_from_categories<? print $rand ?>()
	$('#categorories_selector_for_post_<? print $rand ?> *[name="categories"]').bind('change', function(e){
   mw_load_post_cutom_fields_from_categories<? print $rand ?>()

});
   
 


 
   
});

function mw_load_post_cutom_fields_from_categories<? print $rand ?>(){
var a =	$('#categorories_selector_for_post_<? print $rand ?> *[name="categories"]').val();
var holder1 = $('#custom_fields_from_categorories_selector_for_post_<? print $rand ?>')
if(a == undefined || a == '' || a == '__EMPTY_CATEGORIES__'){
	holder1.empty();
	
} else {
	var cf_cats = a.split(',');
	holder1.empty();
	var i = 1;
	$.each(cf_cats, function(index, value) { 
	
	$new_div_id = 'cf_post_cat_hold_<? print $rand  ?>_'+i+mw.random();
	$new_div = '<div id="'+$new_div_id+'"></div>'
	$new_use_btn = '<button type="button" class="use_'+$new_div_id+'">use</button>'
  holder1.append($new_div); 
		 $('#'+$new_div_id).attr('for','categories');
		 $('#'+$new_div_id).attr('to_table_id',value);
		 
  	     mw.load_module('custom_fields/index','#'+$new_div_id, function(){
			// mw.log(this);
			//	$(this).find('*').addClass('red');
		 	$(this).find('input').attr('disabled','disabled');
			$(this).find('.control-group').append($new_use_btn);
			$('.use_'+$new_div_id).unbind('click');
					$('.use_'+$new_div_id).bind('click', function(e){
						//   mw_load_post_cutom_fields_from_categories<? print $rand ?>()
						$closest =$(this).parent('.control-group').find('*[data-custom-field-id]:first');
						$closest= $closest.attr('data-custom-field-id');
						$('#fields_for_post_<? print $rand  ?>').attr('copy_from',$closest);
						mw.reload_module('#fields_for_post_<? print $rand  ?>');
					 	mw.log($closest );
						 
						return false;
						});
			
			
			
			
			 });
  // $('#'+$new_div_id).find('input').attr('disabled','disabled');
  i++;
  
});
	//holder1.html(a);
	//holder1.children().attr('disabled','disabled');
	
	
}
	
}
</script>
  <?
 $strz = '';
  if(isset($include_categories_in_cat_selector)): ?>
  <? 
 $x = implode(',',$include_categories_in_cat_selector);
 $strz = ' add_ids="'.$x.'" ';   ?>
  <? endif; ?>
  <? if(intval($data['id']) > 0): ?>
  <microweber module="categories/selector" for="content" id="categorories_selector_for_post_<? print $rand ?>" to_table_id="<? print $data['id'] ?>" <? print $strz ?>>
  <? else: ?>
  <microweber module="categories/selector"  id="categorories_selector_for_post_<? print $rand ?>" for="content" <? print $strz ?>>
  <? endif; ?>
  <br />
  Custom fields for post
  <div id="custom_fields_for_post_<? print $rand ?>" >
    <microweber module="custom_fields" view="admin" for="content" to_table_id="<? print $data['id'] ?>" id="fields_for_post_<? print $rand ?>" />
  </div>
  <br />
  Available custom fields
  <div id="custom_fields_from_categorories_selector_for_post_<? print $rand ?>" ></div>
  <? endif; ?>
  <? if($edit_post_mode == false): ?>
  is_home
  <input name="is_home"  type="text" value="<? print ($data['is_home'])?>" />
  <br />
  is_shop
  <select name="is_shop">
    <option value="n"   <? if( '' == trim($data['is_shop']) or 'n' == trim($data['is_shop'])): ?>   selected="selected"  <? endif; ?>>No</option>
    <option value="y"   <? if( 'y' == trim($data['is_shop'])  ): ?>   selected="selected"  <? endif; ?>>Yes</option>
  </select>
  <br />
  subtype
  <select name="subtype">
    <option value="static"   <? if( '' == trim($data['subtype']) or 'static' == trim($data['subtype'])): ?>   selected="selected"  <? endif; ?>>static</option>
    <option value="dynamic"   <? if( 'dynamic' == trim($data['subtype'])  ): ?>   selected="selected"  <? endif; ?>>dynamic</option>
  </select>
  <br />
  subtype_value
  <input name="subtype_value"  type="text" value="<? print ($data['subtype_value'])?>" />
  <br />
  <? endif; ?>
  description
  <input name="description"  type="text" value="<? print ($data['description'])?>" />
  <br />
  <input type="submit" name="save"    value="save" />
  <input type="button" onclick="return false;" id="go_live_edit_<? print $rand ?>" value="go live edit" />
  <? if($edit_post_mode == false): ?>
  <module data-type="content/layout_selector" data-page-id="<? print ($data['id'])?>"  />
  <? endif; ?>
</form>
