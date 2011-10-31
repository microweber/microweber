<?
$id = $params['id'];
 
 
if(intval($id) > 0){
$form_values = get_content($id);
}
 
//p($form_values);
?>



  <? $add_to_category = url_param('add_to_category');
	if($add_to_category  != false){
	$add_to_category  = get_category($add_to_category );
	//p($curent_cat);
	
	
	$page_from_cat = CI::model ( 'content' )->contentsGetTheLastBlogSectionForCategory($add_to_category['id']);
//	p($page_from_cat);
	
	}
	?>
 
 

<div class="formitem">
  <label>Title</label>
  <span class="formfield">
  <input style="width: 100%" id="content_title" name="content_title" onchange="mw.buildURL(this.value, '#content_url')"  type="text" value="<? print $form_values['content_title'] ?>" />
  </span> </div>
<? if($params['parent_page_select']) : ?>
<? $p =   $pages_with_cats =  CI::model ( 'content' )->contentGetPagesWithCategories() ;  ?>
<div class="parent_page_select_for_url"> <strong>Link: </strong>
  <select name="content_parent" id="content_parent">
    <? foreach($p as $p1):?>
    <option  <? if(($form_values['content_parent']) == $p1['id'] or $page_from_cat['id'] == $p1['id']) :  ?>  selected="selected" <? endif; ?>   value="<? print $p1['id'] ?>"><? print page_link($p1['id']); ?></option>
    <? endforeach ?>
  </select>
  / </div>
<input  class="parent_page_select_for_url_field"  name="content_url" id="content_url" type="text"    value="<? print $form_values['content_url'] ?>" />
<br />
<br />
<br />
<div id="select_categories_for_post">
  <microweber module="admin/posts/select_categories_for_post" page_id="<? print $form_values['content_parent'] ?>" post_id="<? print $form_values['id'] ?>"  />
</div>
<br />

<? else : ?>
<div class="formitem">
  <label>URL</label>
  <div id="content_url_page"></div>
  <span class="formfield">
  <input style="width: 100%" name="content_url" id="content_url" type="text"    value="<? print $form_values['content_url'] ?>" />
  </span> </div>
<? endif; ?>
<div id="content_url_page" style="display:none;"></div>

<div class="formitem">
  <label>Description</label>
  <span class="formfield">
  <input style="width: 100%" name="content_description" type="text" value="<? print $form_values['content_description'] ?>" />
  </span> </div>
<div class="formitem">
  <label>Content</label>
  <textarea name="content_body" style="width: 100%" class="richtext" rows="20" cols="150"><? print $form_values['content_body'] ?></textarea>
</div>
