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
$form_rand_id = $rand = uniqid();
?>
<script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 $('#admin_edit_page_form_<? print $form_rand_id ?>').submit(function() { 

 
 mw.form.post($('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
	 
	 mw_after_content_save<? print $rand ?>(this);
	 
	 });
 
  
//  var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');
 	 
		  // mw.reload_module($pmod);

 return false;
 
 
 });
   
   
    $('#go_live_edit_<? print $rand ?>').click(function() { 

 
 mw.form.post($('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
	 
	 
	 
	 
  mw_after_content_save<? print $rand ?>(this);
	 
	 
	 
	 
	 
	 
	 });
	 
	 
	

 return false;
 
 
 });
 
 
 
 
 function mw_after_content_save<? print $rand ?>($id){
	$id = $id.replace(/"/gi, "");
	mw.reload_module('[data-type="pages_menu"]');
	$.get('<? print site_url('api_html/content_link/') ?>'+$id, function(data) {
		//console.log(data);
   window.location.href = data+'/editmode:y';
  
}); 
	
	
	
	 
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
  content_parent
    <? if($edit_post_mode != false): ?>
     <? $pages = get_content('content_type=page');   ?>
    <? else: ?>
    
	 <? $pages = get_content('content_type=page&content_subtype=dynamic');   ?>
	<? endif; ?>
    
    
    
 
  <? if(!empty($pages)): ?>
  <select name="content_parent">
    <option value="0"   <? if((0 == intval($data['content_parent']))): ?>   selected="selected"  <? endif; ?>>None</option>
    <? foreach($pages as $item): ?>
    <option value="<? print $item['id'] ?>"   <? if(($item['id'] == $data['content_parent']) and $item['id'] != $data['id']): ?>   selected="selected"  <? endif; ?>  <? if($item['id'] == $data['id']): ?>    disabled="disabled"  <? endif; ?>  >
    <? print $item['title'] ?>
    </option>
    <? endforeach; ?>
  </select>
  <? endif; ?>
  <br />
 
  <br />
  title
  <input name="title"  type="text" value="<? print ($data['title'])?>" />
  <br />
  content_url
  <input name="content_url"  type="text" value="<? print ($data['content_url'])?>" />
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
  
   <? if($edit_post_mode != false): ?>
   
 <? category_tree(); ?>  
   <? endif; ?>
  
 
   <? if($edit_post_mode == false): ?>
   
   
   
   
  is_home
  <input name="is_home"  type="text" value="<? print ($data['is_home'])?>" />
  <br />
  content_subtype
  <select name="content_subtype">
    <option value="static"   <? if( '' == trim($data['content_subtype']) or 'static' == trim($data['content_subtype'])): ?>   selected="selected"  <? endif; ?>>static</option>
    <option value="dynamic"   <? if( 'dynamic' == trim($data['content_subtype'])  ): ?>   selected="selected"  <? endif; ?>>dynamic</option>
  </select>
  <br />
    content_subtype_value
  <input name="content_subtype_value"  type="text" value="<? print ($data['content_subtype_value'])?>" />
  <br />
  
    <? endif; ?>
  description
  <input name="description"  type="text" value="<? print ($data['description'])?>" />
  <br />

  <input type="submit" name="save" value="save" />
  <input type="button" id="go_live_edit_<? print $rand ?>" value="go live edit" />
  <? if($edit_post_mode == false): ?>
  <module data-type="content/layout_selector" data-page-id="<? print ($data['id'])?>"  />
  <? endif; ?>
</form>
