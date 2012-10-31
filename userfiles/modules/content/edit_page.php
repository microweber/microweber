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
$pid = false;
$data = false;
if(isset($params["data-page-id"]) and intval($params["data-page-id"]) != 0){

$data = get_content_by_id($params["data-page-id"]); 
}
 
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
 </script>
<script  type="text/javascript">


set_page_size = function(){

}

 

$(document).ready(function(){
	
	

mw.$('#admin_edit_page_form_<? print $form_rand_id ?>').submit(function() {

 mw_before_content_save<? print $rand ?>();
 mw.form.post(mw.$('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
	 
	 mw_after_content_save<? print $rand ?>();

 });

  
//  var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');

		  // mw.reload_module($pmod);

 return false;
 
 
 });
   
   
    mw.$('#go_live_edit_<? print $rand ?>').click(function() { 
	

mw_before_content_save<? print $rand ?>()
 
 	<? if(intval($data['id']) == 0): ?>
 mw.form.post(mw.$('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
  mw_after_content_save<? print $rand ?>(this);
});

<? else: ?>
  mw_after_content_save<? print $rand ?>('<? print $data['id'] ?>');

 <? endif; ?>



 return false;
 
 
 });

 
 function mw_before_content_save<? print $rand ?>(){
	mw.$('#admin_edit_page_form_<? print $form_rand_id ?> .module[data-type="custom_fields"]').empty();
 }

 function mw_after_content_save<? print $rand ?>($id){
	
	mw.reload_module('[data-type="pages_menu"]');
	  <? if($edit_post_mode != false): ?>
        mw.reload_module('[data-type="posts"]');
	  <? endif; ?>



	mw.reload_module('#admin_edit_page_form_<? print $form_rand_id ?> .module[data-type="custom_fields"]');
	if($id != undefined){
				$id = $id.replace(/"/g, "");
	    $.get('<? print site_url('api_html/content_link/') ?>'+$id, function(data) {
			   window.location.href = data+'/editmode:y';

		});
	
	}
	

 }






});
</script>

<form  id="admin_edit_page_form_<? print $form_rand_id ?>" class="mw_admin_edit_content_form mw-ui-form">
  <input name="id" type="hidden" value="<? print ($data['id'])?>" />
  <div id="page_title_and_url">
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">Page name</label>
      <input name="title" style="width: 360px;" class="mw-ui-field"  type="text" value="<? print ($data['title'])?>" />
    </div>
    <div class="edit-post-url"> <span class="view-post-site-url"><?php print site_url(); ?></span><span class="view-post-slug active" onclick="mw.slug.toggleEdit()"><? print ($data['url'])?></span>
      <input name="url" class="edit-post-slug" onkeyup="mw.slug.fieldAutoWidthGrow(this);" onblur="mw.slug.toggleEdit();mw.slug.setVal(this);" type="text" value="<? print ($data['url'])?>" />
      <span class="edit-url-ico" onclick="mw.slug.toggleEdit()"></span> </div>
  </div>
  <? /* PAGES ONLY  */ ?>
  <? if($edit_post_mode == false): ?>
  <module data-type="content/layout_selector" data-page-id="<? print ($data['id'])?>"  />
  <? endif; ?>
  <? /* PAGES ONLY  */ ?>
  <? /* ONLY FOR POSTS  */ ?>
  <? if($edit_post_mode != false): ?>
  <script  type="text/javascript">

 


$(document).ready(function(){

    mw_load_post_cutom_fields_from_categories<? print $rand ?>()
    mw.$('#categorories_selector_for_post_<? print $rand ?> *[name="categories"]').bind('change', function(e){
    mw_load_post_cutom_fields_from_categories<? print $rand ?>();





});
   
 


 
   
});

function mw_load_post_cutom_fields_from_categories<? print $rand ?>(){
var a =	mw.$('#categorories_selector_for_post_<? print $rand ?> *[name="categories"]').val();
var holder1 = mw.$('#custom_fields_from_categorories_selector_for_post_<? print $rand ?>')
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
		 mw.$('#'+$new_div_id).attr('for','categories');
		 mw.$('#'+$new_div_id).attr('to_table_id',value);

  	     mw.load_module('custom_fields/index','#'+$new_div_id, function(){
			// mw.log(this);
			//	$(this).find('*').addClass('red');
		 	$(this).find('input').attr('disabled','disabled');
			$(this).find('.control-group').append($new_use_btn);
			mw.$('.use_'+$new_div_id).unbind('click');
					mw.$('.use_'+$new_div_id).bind('click', function(e){
						//   mw_load_post_cutom_fields_from_categories<? print $rand ?>()
						$closest =$(this).parent('.control-group').find('*[data-custom-field-id]:first');
						$closest= $closest.attr('data-custom-field-id');
						mw.$('#fields_for_post_<? print $rand  ?>').attr('copy_from',$closest);
						mw.reload_module('#fields_for_post_<? print $rand  ?>');
					 	mw.log($closest );
						 
						return false;
						});
			
			
			
			
			 });
  // mw.$('#'+$new_div_id).find('input').attr('disabled','disabled');
  i++;
  
});
	//holder1.html(a);
	//holder1.children().attr('disabled','disabled');
	
	
}
	
}
</script>
  <?
  
   $shopstr = '&is_shop=n';
   
   
   if(isset($params["subtype"]) and $params["subtype"] == 'product'){
	   $shopstr = '&is_shop=y';
}
    if(isset($params["data-subtype"]) and $params["data-subtype"] == 'product'){
	   $shopstr = '&is_shop=y';
}

    if(isset($data["subtype"]) and $data["subtype"] == 'product'){
	   $shopstr = '&is_shop=y';
}
  
 $strz = '';
  if(isset($include_categories_in_cat_selector)): ?>
  <?
 $x = implode(',',$include_categories_in_cat_selector);
 $strz = ' add_ids="'.$x.'" ';   ?>
  <? endif; ?>
  <div class="mw-ui mw-ui-category-selector">
    <? if(intval($data['id']) > 0): ?>
    <microweber module="categories/selector" for="content" id="categorories_selector_for_post_<? print $rand ?>" to_table_id="<? print $data['id'] ?>" <? print $strz ?> <? print $shopstr ?> />
    <? else: ?>
    <microweber module="categories/selector"  id="categorories_selector_for_post_<? print $rand ?>" for="content" <? print $strz ?> <? print $shopstr ?> />
    <? endif; ?>
  </div>
  <? endif; ?>
  <? /* ONLY FOR POSTS  */ ?>
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
  <input name="subtype"  type="hidden"  value="<? print $params['subtype'] ?>" >
  <? endif; ?>
  <div class="mw-ui-select">
    <select name="parent">
      <?    
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['actve_ids'] = $data['parent'];
$pt_opts['remove_ids'] = $data['id'];
 
 
$pt_opts['active_code_tag'] = '   selected="selected"  ';
 



 pages_tree($pt_opts);  ?>
    </select>
  </div>
  <? if($edit_post_mode != false): ?>
  <? $data['content_type'] = 'post'; ?>
  <module type="pictures" view="admin" for="content" for-id=<? print $data['id'] ?> />
  <? endif; ?>
  <input name="content_type"  type="hidden"  value="<? print $data['content_type'] ?>" >
  <div class="mw_save_buttons_holder">
    <input type="submit" name="save"    value="save" />
    <input type="button" onclick="return false;" id="go_live_edit_<? print $rand ?>" value="go live edit" />
  </div>
  <? /* ONLY FOR POSTS  */ ?>
  <? if($edit_post_mode != false): ?>
  Custom fields for post
  <div id="custom_fields_for_post_<? print $rand ?>" >
    <microweber module="custom_fields" view="admin" for="content" to_table_id="<? print $data['id'] ?>" id="fields_for_post_<? print $rand ?>" />
  </div>
  <br />
  Available custom fields
  <div id="custom_fields_from_categorories_selector_for_post_<? print $rand ?>" ></div>
  <? endif; ?>
  <? /* ONLY FOR POSTS  */ ?>
  <div class="advanced_settings"> <a href="javascript:;" onclick="ToggleAdvancedSettings();"  class="toggle_advanced_settings mw-ui-more">
    <?php _e('Advanced Settings'); ?>
    </a>
    <div class="advanced_settings_holder">
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Description</label>
        <textarea class="mw-ui-field" name="description"><? print ($data['description'])?></textarea>
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Meta Keywords</label>
        <textarea class="mw-ui-field" name="metakeys">Some keywords</textarea>
      </div>
      <? /* PAGES ONLY  */ ?>
      <? if($edit_post_mode == false): ?>
      <br />
      <br />
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Home?</div>
        <label class="mw-ui-check">
          <input name="is_home" type="radio"  value="n" <? if( '' == trim($data['is_home']) or 'n' == trim($data['is_home'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_home" type="radio"  value="y" <? if( 'y' == trim($data['is_home'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <br class="mw-clear" />
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Shop?</div>
        <label class="mw-ui-check">
          <input name="is_shop" type="radio"  value="n" <? if( '' == trim($data['is_shop']) or 'n' == trim($data['is_shop'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_shop" type="radio"  value="y" <? if( 'y' == trim($data['is_shop'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <br />
      <br />
      subtype
      <div class="mw-ui-select">
        <select name="subtype">
          <option value="static"   <? if( '' == trim($data['subtype']) or 'static' == trim($data['subtype'])): ?>   selected="selected"  <? endif; ?>>static</option>
          <option value="dynamic"   <? if( 'dynamic' == trim($data['subtype'])  ): ?>   selected="selected"  <? endif; ?>>dynamic</option>
        </select>
      </div>
      <br />
      <input name="subtype_value"  type="hidden" value="<? print ($data['subtype_value'])?>" />
      <br />
      <? endif; ?>
      <? /* PAGES ONLY  */ ?>
      <br />
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Active?</div>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="n" <? if( '' == trim($data['is_active']) or 'n' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="y" <? if( 'y' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <br />
      <br />
      <br />
      <br />
      <br />
      <? if($edit_post_mode == false): ?>
      <? endif; ?>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Password</label>
        <input name="password" style="width: 360px;" class="mw-ui-field"  type="password" value="" />
      </div>
      <br />
      <br />
      <button type="submit">Save be</button>
    </div>
  </div>
</form>
