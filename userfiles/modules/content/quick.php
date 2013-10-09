<?php


$rand = uniqid(); 
$data = false;
//$data = $params;
$is_new_content = false;
if(!isset($is_quick)){
$is_quick=false;	
}
if(isset($params['page-id'])){
  $data = mw('content')->get_by_id(intval($params["page-id"]));
} 
if(isset($params['content-id'])){
  $data = mw('content')->get_by_id(intval($params["content-id"]));
}




$title_placeholder = false;
/* FILLING UP EMPTY CONTENT WITH DATA */
if($data == false or empty($data )){
   $is_new_content = true;
   include('_empty_content_data.php');
}
/* END OF FILLING UP EMPTY CONTENT  */

if(intval($data['id']) == 0 and intval($data['parent']) == 0 and isset($params['parent-page-id'])){
	
	  $data['parent'] = $params['parent-page-id'];

}
 

/* CREATING DEFAULT BLOG OR SHOP IF THEY DONT EXIST */
if(intval($data['id']) == 0 and intval($data['parent']) == 0){
	
	$parent_content_params = array();
	$parent_content_params['subtype'] = 'dynamic';
	$parent_content_params['content_type'] = 'page';
	$parent_content_params['limit'] = 1;
	$parent_content_params['one'] = 1;
	$parent_content_params['fields'] = 'id';
	$parent_content_params['order_by'] = 'updated_on desc';
	if(isset($params['subtype']) and $params['subtype'] == 'post'){
		$parent_content_params['is_shop'] = 'n';
	    $parent_content = get_content($parent_content_params);
		 if(isset($parent_content['id'])){
			 $data['parent'] = $parent_content['id'];
		 } else {
			  mw('content')->create_default_content('blog');
			  $parent_content_params['no_cache'] = true;
			  $parent_content = get_content($parent_content_params);
		 }
	} elseif(isset($params['subtype']) and $params['subtype'] == 'product'){
		$parent_content_params['is_shop'] = 'y';
	    $parent_content = get_content($parent_content_params);
		 if(isset($parent_content['id'])){
			 $data['parent'] = $parent_content['id'];
		 } else {
			  mw('content')->create_default_content('shop');
			  $parent_content_params['no_cache'] = true;
			  $parent_content = get_content($parent_content_params);
		 }
	} 
	if(isset($parent_content) and isset($parent_content['id'])){
			 $data['parent'] = $parent_content['id'];
	 } 

}
/* END OF CREATING DEFAULT BLOG OR SHOP IF THEY DONT EXIST */












 $module_id = $params['id'];
 
?>
 
<form method="post" class="mw_admin_edit_content_form" action="<?php print site_url(); ?>api/save_content" id="quickform-<?php print $rand; ?>">
	<input type="hidden" name="id" id="mw-content-id-value"  value="<?php print $data['id']; ?>" />
	<input type="hidden" name="is_active"  value="y" />
	<input type="text" name="subtype" id="mw-content-subtype-value"   value="<?php print $data['subtype']; ?>" />
	<input type="hidden" name="content_type" id="mw-content-type-value"   value="<?php print $data['content_type']; ?>" />
	<input type="text" name="parent"  id="mw-parent-page-value" value="<?php print $data['parent']; ?>" />

		<input type="text" name="is_shop"  id="mw-is-shop-value" value="<?php print $data['is_shop']; ?>" />

	
	
	<div class="mw-ui-field-holder">
		<input
      type="text"
      name="title"
      class="mw-ui-field mw-title-field mw-ui-field-full"
      style="border-left: 1px solid #E6E6E6"
      placeholder="<?php print $title_placeholder; ?>"
	  value="<?php print $data['title']; ?>" 
      autofocus required />
	</div>
	<div class="mw-ui-field-holder">
		<div>
			<div class="mw-ui-field mw-tag-selector mw-ui-field-dropdown mw-ui-field-full" id="mw-post-added-<?php print $rand; ?>">
				<input type="text" class="mw-ui-invisible-field" placeholder="<?php _e("Click here to add to categories and pages"); ?>." style="width: 280px;" id="quick-tag-field" />
			</div>
			<div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector" id="mw-category-selector-<?php print $rand; ?>" >
				
				
					<?php if($data['content_type'] == 'page'){ ?>
					<module type="content/selector" field-name="parent_id_selector" change-field="parent" selected-id="<?php print $data['parent']; ?>"   />
					<?php } ?>
					<?php if($data['content_type'] != 'page' and $data['subtype'] != 'category'){ ?>
					<module
                    type="categories/selector"
                    for="content"
					active_ids="<?php print $data['parent']; ?>"
					subtype="<?php print $data['subtype']; ?>"
					for-id="<?php print $data['id']; ?>" />
					<?php } ?>
					
					
					
					
			</div>
		</div>
	</div>
	<?php if($data['content_type'] == 'post' or $data['subtype'] == 'post' or $data['subtype'] == 'product'){ ?>
	<div class="mw-ui-field-holder">
		<textarea class="semi_hidden" name="content" id="quick_content_<?php print $rand ?>"></textarea>
	</div>
	<?php } ?>
	<?php if($data['content_type'] == 'page'){ ?>
	<module type="content/layout_selector" id="mw-quick-add-choose-layout" autoload="yes" content-id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" />
	<?php } ?>
	<?php if($data['subtype'] == 'product'){ ?>
	<div class="mw-ui-field-holder">
		<module
          type="custom_fields/admin"
          for="content"
          default-fields="price"
          content-id="0"
          content-subtype="<?php print $data['subtype'] ?>" />
	</div>
	<?php } ?>
	<div class="mw-ui-field-holder">
		<?php if($data['subtype'] != 'category'){ ?>
		<span class="mw-ui-link relative" id="quick-add-gallery" onclick="mw.$('#quick_init_gallery').show();$(this).hide();">Create Gallery</span>
		<div id="quick_init_gallery" class="mw-o-box" style="display: none;margin-bottom:20px;">
			<div  class="mw-o-box-content">
				<module type="pictures/admin" content_id="0" rel="content" rel_id="0" />
			</div>
		</div>
		<?php } ?>
		
		
	
		
		
		
		
		
		
		
		
		
		
		<button type="submit" class="mw-ui-btn mw-ui-btn-green right">Publish</button>
	</div>
</form>
<div class="quick_done_alert" style="display: none">
	<h2><span style="text-transform: capitalize"><?php print $data['subtype'] ?></span> has been created.</h2>
	<a href="javascript:;" class="mw-ui-link">Go to <?php print $data['subtype'] ?></a> <span class="mw-ui-btn" onclick="$(mw.tools.firstParentWithClass(this, 'mw-inline-modal')).remove();">Create New</span> </div>
<script>
    mw.require("content.js");
    mw.require("files.js");
</script> 
<script>
/* FUNCTIONS */


load_iframe_editor =   function(element_id){



	 var element_id =  element_id || 'quick_content_<?php print $rand ?>';
	 var area = mwd.getElementById(element_id);
	 
	 
	 
	 var parent_page =  mw.$('#mw-parent-page-value').val();
	 var content_id =  mw.$('#mw-content-id-value').val();
	 var content_type =  mw.$('#mw-content-type-value').val();
	 var subtype =  mw.$('#mw-content-subtype-value').val();

	 
	

	 if(area !== null){
		var params = {};
		params.content_id=content_id
		params.content_type=content_type
		params.subtype=subtype
		params.parent_page=parent_page
		params.inherit_template_from=parent_page
		if(typeof editor !== "undefined" && editor !== null){
			 $(editor).remove();	
			  delete window.editor 
			 
		}
		
		
		

		editor =  mw.tools.wysiwyg(area,params ,true);
        editor.style.width = "100%";
        editor.style.height = "470px";
		



	 }
	 
	 
	 var layout_selector =  mw.$('#mw-quick-add-choose-layout')
	  if(layout_selector !== null){
		  layout_selector.attr('inherit_from',parent_page);
		  mw.reload_module('#mw-quick-add-choose-layout');
		  
	  }
	 
	 
	 

}



set_parent_and_category = function(){
      /* FILLING UP THE HIDDEN FIELDS as you change category or parent page */	
	  
	  var names = [];
      var inputs = mwd.getElementById('mw-category-selector-<?php print $rand; ?>').querySelectorAll('input[type="checkbox"]'), i=0, l = inputs.length;
      for( ; i<l; i++){
        if(inputs[i].checked === true){
           names.push(inputs[i].value);
        }
      }
      if(names.length > 0){
        mw.$('#mw_cat_selected_for_post').val(names.join(',')).trigger("change");
      } else {
        mw.$('#mw_cat_selected_for_post').val('__EMPTY_CATEGORIES__').trigger("change");
      }
	  
	  
	  var names = [];
      var inputs = mwd.getElementById('mw-category-selector-<?php print $rand; ?>').querySelectorAll('input[type="radio"]'), i=0, l = inputs.length;
      for( ; i<l; i++){
        if(inputs[i].checked === true){
           names.push(inputs[i].value);
        }
      }
      if(names.length > 0){
        mw.$('#mw-parent-page-value').val(names[0]).trigger("change");
      } else {
        mw.$('#mw-parent-page-value').val(0).trigger("change");
      }
	
	  
    }
	
	
/* END OF FUNCTIONS */	

</script>
<script>



    



    $(document).ready(function(){
		 load_iframe_editor();
		if(mw.treeRenderer != undefined){
			   mw.treeRenderer.appendUI('#mw-category-selector-<?php print $rand; ?>');
			   mw.tools.tag({
				  tagholder:'#mw-post-added-<?php print $rand; ?>',
				  items: ".mw-ui-check",
				  itemsWrapper: mwd.querySelector('#mw-category-selector-<?php print $rand; ?>'),
				  method:'parse',
				  onTag:function(){
					 set_parent_and_category()
				  },
				  onUntag:function(){
					 set_parent_and_category()
				  }
			  });
		  
		  
		}
      mw.$("#quickform-<?php print $rand; ?>").submit(function(){
        var el = this;
        var module =  $(mw.tools.firstParentWithClass(el, 'module'));
        var data = mw.serializeFields(el);
        module.addClass('loading');
        mw.content.save(data, {
          onSuccess:function(){
             // el.reset();
             // $(editor).contents().find("#mw-iframe-editor-area").empty();
			 
			 
			 
			 mw.$("#<?php print $module_id ?>").attr("content-id",this);
			 mw.reload_module('#<?php print $module_id ?>');
			 
			   
			 
			 
			 
			 
			 
			 
			 
/*              mw.$(".quick_done_alert a").attr("href", mw.settings.site_url + "?content_id=" + this);
              mw.reload_module("pictures/admin", function(){
                  module.removeClass('loading');
                 
              });
			   mw.tools.inlineModal({
                    element: mw.$(".quick-add-module"),
                    content: $(".quick_done_alert")
                  });
*/				  
				  
				  
				  
				  
				  
				  
          },
          onError:function(){
              module.removeClass('loading');
              if(typeof this.title !== 'undefined'){
                mw.notification.error('Please enter title');
              }
              if(typeof this.content !== 'undefined'){
                mw.notification.error('Please enter content');
              }
              if(typeof this.error !== 'undefined'){
                mw.session.checkPause = false;
                mw.session.checkPauseExplicitly = false;
                mw.session.logRequest();
              }
          }
        })
        return false;
      });
	  
	  
	  
	  
	  
	  
	 /* reloading the layout selector on parent change */	
	 mw.$('#mw-parent-page-value').bind('change', function(e){
			 //var val = $(this).val();
			 //alert(val);
			 load_iframe_editor();

  	});

	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
    });
</script>















