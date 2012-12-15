<?
if(!isset($edit_post_mode)){
	$edit_post_mode = false;
}   //  $params['content_type'] = 'post';

 



if(isset($params["data-content-id"])){
	$params["data-page-id"] = $params["data-content-id"];
}

if(isset($params["content-id"])){
	$params["data-page-id"] = $params["content-id"];
}
if(isset($params["data-content"])){
	$params["data-page-id"] = $params["data-content"];
}

if(!isset($params["data-page-id"])){
	$params["data-page-id"] = PAGE_ID;
}
 
$pid = false;
$data = false;
if(isset($params["data-page-id"]) and intval($params["data-page-id"]) != 0){

$data = get_content_by_id(intval($params["data-page-id"])); 

}


if($data == false or empty($data )){
include('_empty_content_data.php');
}
if(isset($edit_post_mode) and $edit_post_mode == true){
             $data['content_type'] = 'post';
}
if(isset($data['content_type']) and  $data['content_type'] == 'post'){
	$edit_post_mode = true;
}


if(isset($params["data-is-shop"])){
	$data["is_shop"] = $params["data-is-shop"];
}




$form_rand_id = $rand = uniqid();
?>
<script  type="text/javascript">
  mw.require('forms.js');
  mw.require('url.js');
 </script>
<script type="text/javascript">




$(document).ready(function(){
	


mw.$('#admin_edit_page_form_<? print $form_rand_id ?>').submit(function() {




 mw_before_content_save<? print $rand ?>();
 mw.form.post(mw.$('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
                        	<? if(intval($data['id']) == 0): ?>


                            mw.url.windowHashParam("action", "edit<? print $data['content_type'] ?>:" + this);

							 mw.url.windowHashParam("new_content", 'true');
							 mw.reload_module('[data-type="pages_menu"]');
  <? else: ?>
 mw_after_content_save<? print $rand ?>();
                             <? endif; ?>
	

 });


//  var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');

		  // mw.reload_module($pmod);

 return false;
 
 
 });
   

    mw.$('#admin_edit_page_form_<? print $form_rand_id ?> a.go-live').click(function() {
	

mw_before_content_save<? print $rand ?>()

  
 mw.form.post(mw.$('#admin_edit_page_form_<? print $form_rand_id ?>') , '<? print site_url('api/save_content') ?>', function(){
 mw_after_content_save<? print $rand ?>(this);




});

 


 return false;
 

 });




  




});


	  mw.on.hashParam("new_content", function(){

     //alert(' mw_on_save_complete<? print $rand ?>')
	 mw_on_save_complete<? print $rand ?>();
	
//mw.url.windowDeleteHashParam("new_content")
 });
 

 
 function mw_on_save_complete<? print $rand ?>(){
	//alert(1);
    mw.notification.success("<?php _e('All changes are saved'); ?>.");
 }


 
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
			
			
			
			mw_on_save_complete<? print $rand ?>()
			
	
	
		}
</script>

<form autocomplete="off" name="mw_edit_page_form" id="admin_edit_page_form_<? print $form_rand_id ?>" class="mw_admin_edit_content_form mw-ui-form add-edit-page-post content-type-<? print $data['content_type'] ?>">
  <input name="id" type="hidden" value="<? print ($data['id'])?>" />
  <div id="page_title_and_url">
    <div class="mw-ui-field-holder">
      <?php if($data['content_type'] == 'post' or $data['content_type'] == 'product' ): ?>
      <div class="mw-ui-field-holder post-save-top right">
        <input type="submit" name="save" class="semi_hidden"  value="Save" />
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium go-live">
        <?php _e("Go live edit"); ?>
        </a> <a href="javascript:;" style="min-width: 66px;" onclick="$(document.forms['mw_edit_page_form']).submit();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green">
        <?php _e("Save"); ?>
        </a> </div>
      <?php endif; ?>
      <? if(intval($data['id']) > 0): ?>
      <? $act = _e("Edit ", true); ;?>
      <? else : ?>
      <? $act = _e('Add new ', true); ;?>
      <? endif; ?>
      <?php /*         */
if(intval($data['id']) == 0 and isset($params['subtype']) and trim($params['subtype']) != '') {
		  $data['subtype'] = $params['subtype'];
		  $t =      $data['subtype'];
	} else if($data['content_type'] == 'post'){
		
     //   $data['subtype'] = 'post';
        $t =      $data['subtype'];
     } else {
            $t =      $data['content_type'];
     }


 ?>
      <label class="mw-ui-label" style="padding-top: 5px;"><?php print ucfirst( $t); ?> Name</label>
      <? if(intval($data['id']) > 0): ?>
      <input name="title" class="mw-ui-field mw-title-field"  type="text" value="<? print ($data['title'])?>" />
      <? else : ?>
      <?
    //d($params);

     ?>
      <input name="title" class="mw-ui-field mw-title-field"   type="text" value="<?php print ucfirst($t); ?> title" />
      <? endif; ?>
    </div>
    <div class="edit-post-url"> <span class="view-post-site-url"><?php print site_url(); ?></span><span class="view-post-slug active" onclick="mw.slug.toggleEdit()"><? print ($data['url'])?></span>
      <input name="content_url" class="edit-post-slug" onkeyup="mw.slug.fieldAutoWidthGrow(this);" onblur="mw.slug.toggleEdit();mw.slug.setVal(this);" type="text" value="<? print ($data['url'])?>" />
      <span class="edit-url-ico" onclick="mw.slug.toggleEdit()"></span> </div>
  </div>
  <? /* PAGES ONLY  */ ?>
  <? if($edit_post_mode == false): ?>
  <module data-type="content/layout_selector" data-page-id="<? print ($data['id'])?>"  />
  <? if($edit_post_mode == false): ?>
  <?   //  d($data);
  
  $pt_opts = array();
  if(intval($data['id']) > 0){
$pt_opts['active_ids'] = $data['parent'];	
	
} else {

 if(isset($params['parent-page-id']) and intval($data['parent']) == 0 and intval($params['parent-page-id']) > 0){
	 $pt_opts['active_ids'] = $data['parent']= $params['parent-page-id'];
}
	
	
}
  
 
   ?>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Parent page</label>
    <div class="mw-ui-select" style="width: 364px;">
      <select name="parent">
        <option value="0"   <? if((0 == intval($data['parent']))): ?>   selected="selected"  <? endif; ?>>None</option>
        <?

$pt_opts['link'] = "{empty}{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['remove_ids'] = $data['id'];
if(isset($params['is_shop'])){
//$pt_opts['is_shop'] = $params['is_shop'];
}
 
 

$pt_opts['active_code_tag'] = '   selected="selected"  ';
 

 
 pages_tree($pt_opts);


  ?>
      </select>
    </div>
  </div>
  <? endif; ?>
  <? endif; ?>
  <? /* PAGES ONLY  */ ?>
  <? /* ONLY FOR POSTS  */ ?>
  <? if($edit_post_mode != false): ?>
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
 
 
  $selected_parent_ategory_id = '';
  if(isset($params["parent-category-id"])){
	   $selected_parent_ategory_id = " data-parent-category-id={$params["parent-category-id"]} ";
}
  


 
 
 
  if(isset($include_categories_in_cat_selector)): ?>
  <?
 $x = implode(',',$include_categories_in_cat_selector);
 $strz = ' add_ids="'.$x.'" ';   ?>
  <? endif; ?>
  
  <? $categories_active_ids = ''; ?>
  <label class="mw-ui-label">
    <?php _e("Select Category"); ?>
  </label>
  <div class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector">
    <div class="cat_selector_view_ctrl"><a href="javascript:;" class="active" onclick="mw.$('#categorories_selector_for_post_<? print $rand ?> label.mw-ui-check').show();$(this).addClass('active').next().removeClass('active');">All</a> <a href="javascript:;" onclick="mw.tools.tree.viewChecked(mwd.getElementById('categorories_selector_for_post_<? print $rand ?>'));$(this).addClass('active').prev().removeClass('active');">Selected</a> </div>
    <? if(intval($data['id']) > 0): ?>
    
    
    <? $in_cats = get('from=taxonomy_items&fields=parent_id&to_table=table_content&to_table_id='.$data['id']);
	if(isarr($in_cats)){
	foreach($in_cats as $in_cat){
		$categories_active_ids = $categories_active_ids.','.$in_cat['parent_id'];
	}
	}
	 // d($categories_active_ids);
	 ?>
    
    
    
    
    <microweber module="categories/selector"  categories_active_ids="<? print $categories_active_ids; ?>" for="content" id="categorories_selector_for_post_<? print $rand ?>" to_table_id="<? print $data['id'] ?>"  active_ids="<? print intval($data['parent']) ?>" <? print $strz ?> <? print $shopstr ?> />
    <? else: ?>
    <? if(isset($params["parent-page-id"]) and intval($params["parent-page-id"]) > 0){
		 
		 $selected_parent_ategory_id = 'active_ids="'.$params["parent-page-id"].'"';
	 } ?>
     
     
     
      
    <microweber module="categories/selector"   categories_active_ids="<? print $categories_active_ids; ?>"  id="categorories_selector_for_post_<? print $rand ?>" to_table_id="<? print $data['id'] ?>"  active_ids="<? print intval($data['parent']) ?>" for="content" <? print $strz ?> <? print $selected_parent_ategory_id ?> <? print $shopstr ?> />
    <? endif; ?>
  </div>
  <script type="text/javascript">
    $(mwd).ready(function(){
		if(!!mw.treeRenderer){
             mw.treeRenderer.appendUI('#categorories_selector_for_post_<? print $rand ?>');
		}
    });
  </script>
  <? endif; ?>
  <? /* ONLY FOR POSTS  */ ?>
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
  <?
 if(intval($data['id']) == 0){
 if(isset($params["parent-page-id"]) and intval($params["parent-page-id"]) != 0){
			  $data['parent'] = $params["parent-page-id"];
			   
		   }
 }
// d(  $data['parent']);
  ?>
  <? if(!isset($params['subtype'])): ?>
  <?   $params['subtype'] = 'post'; ?>
  <? endif; ?>
  <input name="subtype"  type="hidden"  value="<? print $data['subtype'] ?>" >
  <? endif; ?>
  <?

 

?>
  <? if($edit_post_mode != false): ?>
  <? $data['content_type'] = 'post'; ?>
  <module type="pictures" view="admin" for="content" for-id=<? print $data['id'] ?>  />
  <? endif; ?>
  <? exec_action('mw_edit_content_admin', $data); ?>
  <? if($edit_post_mode != false): ?>
  <? exec_action('mw_edit_post_admin', $data); ?>
  <? else: ?>
  <? exec_action('mw_edit_page_admin', $data); ?>
  <? endif; ?>
  <input name="content_type"  type="hidden"  value="<? print $data['content_type'] ?>" >
  <? if($edit_post_mode != false): ?>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
  <? endif; ?>
  <? /* ONLY FOR POSTS  */ ?>
  <? // if($edit_post_mode != false): ?>
  <div class="vSpace"></div>
  <a href="javascript:;" class="mw-ui-more" onclick="mw.tools.toggle('#custom_fields_for_post_<? print $rand ?>', this);">
  <?php _e("Custom Fields"); ?>
  </a>
  <?php /* <a href="javascript:;" class="mw-ui-btn-rect" onclick="mw.tools.toggle('#the_custom_fields', this);"><span class="ico iSingleText"></span><?php _e("Custom Fields"); ?></a>  */ ?>
  <div class="vSpace"></div>
  <div id="custom_fields_for_post_<? print $rand ?>" >
    <module type="custom_fields/admin"    for="table_content" to_table_id="<? print $data['id'] ?>" id="fields_for_post_<? print $rand ?>" content-subtype="<? print $data['subtype'] ?>" />
    <script  type="text/javascript">
$(document).ready(function(){

		  mw_load_post_cutom_fields_from_categories<? print $rand ?>()
			mw.$('#categorories_selector_for_post_<? print $rand ?> input').bind('change', function(e){
		   mw_load_post_cutom_fields_from_categories<? print $rand ?>();
		
		
		
		
		
		});
});

function mw_load_post_cutom_fields_from_categories<? print $rand ?>(){
 var vals = mw.$('#categorories_selector_for_post_<? print $rand ?> input[name="parent"]:checked').val();
 var holder1 = mw.$('#custom_fields_from_categorories_selector_for_post_1<? print $rand ?>');
 if(vals != undefined){
	 i = 1;
		 
				 holder1.attr('for','table_content');
				 holder1.attr('save_to_content_id','<? print $data['id'] ?>');
				 holder1.attr('to_table_id',vals);
				 
		
				 mw.load_module('custom_fields/list','#custom_fields_from_categorories_selector_for_post_1<? print $rand ?>', function(){

				 // holder1.find('[name="to_table_id"]').val('<? print $data['id'] ?>');
					 });
	 
 }
 
 
 
 
 
 
// mw.log(vals);
	
} 
</script>
    <div id="custom_fields_from_categorories_selector_for_post_1<? print $rand ?>" ></div>
  </div>
  <div class="vSpace"></div>
  <? //endif; ?>
  <div class="mw_clear">&nbsp;</div>
  <? /* ONLY FOR POSTS  */ ?>
  <div class="advanced_settings"> <a href="javascript:;" onclick="mw.tools.toggle('.advanced_settings_holder', this);"  class="toggle_advanced_settings mw-ui-more">
    <?php _e('Advanced Settings'); ?>
    </a>
    <?php /* <a href="javascript:;" onclick="mw.tools.toggle('.advanced_settings_holder', this);"  class="toggle_advanced_settings mw-ui-btn-rect">
       <span class="ico ioptions"></span> <?php _e('Advanced Settings'); ?>
    </a> */ ?>
    <div class="advanced_settings_holder">
      <? if($edit_post_mode == false): ?>
      <microweber module="pictures" view="admin" for="content" for-id=<? print $data['id'] ?> />
      <? endif; ?>
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
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Active?</div>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="n" <? if( '' == trim($data['is_active']) or 'n' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="y" <? if( 'y' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <div class="mw_clear vSpace"></div>
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Home?</div>
        <label class="mw-ui-check">
          <input name="is_home" type="radio"  value="n" <? if( '' == trim($data['is_home']) or 'n' == trim($data['is_home'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_home" type="radio"  value="y" <? if( 'y' == trim($data['is_home'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <div class="mw_clear vSpace"></div>
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Shop?</div>
        <label class="mw-ui-check">
          <input name="is_shop" type="radio"  value="n" <? if( '' == trim($data['is_shop']) or 'n' == trim($data['is_shop'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_shop" type="radio"  value="y" <? if( 'y' == trim($data['is_shop'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <div class="mw_clear vSpace"></div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Page type</label>
        <div class="mw-ui-select" style="width: 364px;">
          <select name="subtype">
            <option value="static"   <? if( '' == trim($data['subtype']) or 'static' == trim($data['subtype'])): ?>   selected="selected"  <? endif; ?>>static</option>
            <option value="dynamic"   <? if( 'dynamic' == trim($data['subtype'])  ): ?>   selected="selected"  <? endif; ?>>dynamic</option>
          </select>
        </div>
      </div>
      <input name="subtype_value"  type="hidden" value="<? print ($data['subtype_value'])?>" />
      <? endif; ?>
      <? /* PAGES ONLY  */ ?>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Password</label>
        <input name="password" style="width: 360px;" class="mw-ui-field" type="password" value="" />
      </div>
    </div>
    <div class="mw_clear vSpace"></div>
    <div class="post-save-bottom">
      <input type="submit" name="save" class="semi_hidden"  value="Save" />
      <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium go-live">
      <?php _e("Go live edit"); ?>
      </a> <a href="javascript:;" style="min-width: 66px;" onclick="$(document.forms['mw_edit_page_form']).submit();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green">
      <?php _e("Save"); ?>
      </a> </div>
  </div>
</form>
