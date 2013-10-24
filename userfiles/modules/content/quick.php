<?php

  only_admin_access();

  $rand = uniqid();
  $data = false;
  $just_saved = false;
  $is_new_content = false;
  $is_current = false;
  $is_live_edit = false;
  if(!isset($is_quick)){
      $is_quick=false;
  }
  if(isset($params['live_edit'])){
    $is_live_edit = $params['live_edit'];
  }
  
  if(isset($params['quick_edit'])){
    $is_quick = $params['quick_edit'];
  }
  if(isset($params['just-saved'])){
    $just_saved = $params['just-saved'];
  }
  if(isset($params['is-current'])){
    $is_current = $params['is-current'];
  }
  if(isset($params['page-id'])){
    $data = get_content_by_id(intval($params["page-id"]));
  }
  if(isset($params['content-id'])){
    $data = get_content_by_id(intval($params["content-id"]));
  }
  $recommended_parent = false;
  if(isset($params['recommended_parent']) and $params['recommended_parent'] != false){
    $recommended_parent = $params['recommended_parent'];
  }
  $categories_active_ids =false;
  $title_placeholder = false;

  /* FILLING UP EMPTY CONTENT WITH DATA */
  if($data == false or empty($data )){
     $is_new_content = true;
     include('_empty_content_data.php');
  }

/* END OF FILLING UP EMPTY CONTENT  */


/* SETTING PARENT AND ACTIVE CATEGORY */

if(intval($data['id']) == 0 and intval($data['parent']) == 0 and isset($params['parent-page-id'])){
    $data['parent'] = $params['parent-page-id'];
    if(isset($params['subtype']) and $params['subtype'] == 'product'){
        $parent_content = get_content_by_id($params['parent-page-id']);
        if(!isset($parent_content['is_shop']) or $parent_content['is_shop'] != 'y'){
            $data['parent'] = 0;
        }
    }
    if(isset($params['parent-category-id']) and $params['parent-category-id'] != 0){
        $categories_active_ids =$params['parent-category-id'];
    }
}
else if(intval($data['id']) != 0){
    $categories  =get_categories_for_content($data['id']);
   if(is_array($categories)){
	 $c = array();
	 foreach($categories as $category){
		 $c[] = $category['id'];
	 }
 	 $categories_active_ids = implode(',',$c);
    }

}
/* END OF SETTING PARENT AND ACTIVE CATEGORY  */

 

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
<?php if($just_saved!=false) : ?>

Well done, you have saved your changes.

Go to see them at this link

<a target="_top" class="btn" href="<?php print content_link($data['id']); ?>?editmode=y"><?php print content_link($data['id']); ?></a> Or create new content


<?php endif; ?>
<form method="post" <?php if($just_saved!=false) : ?> style="display:none;" <?php endif; ?> class="mw_admin_edit_content_form" action="<?php print site_url(); ?>api/save_content" id="quickform-<?php print $rand; ?>">
	<input type="hidden" name="id" id="mw-content-id-value"  value="<?php print $data['id']; ?>" />
	<input type="hidden" name="subtype" id="mw-content-subtype-value"   value="<?php print $data['subtype']; ?>" />
	<input type="hidden" name="content_type" id="mw-content-type-value"   value="<?php print $data['content_type']; ?>" />
	<input type="hidden" name="parent"  id="mw-parent-page-value" value="<?php print $data['parent']; ?>" />
    <div class="mw-ui-field-holder"  style="padding-bottom: 5px;">
	    <span class="mw-title-field-label mw-title-field-label-<?php print $data['subtype']; ?>"></span>
		<input
              autofocus
              type="text"
              name="title"
              placeholder="<?php print $title_placeholder; ?>"
              class="mw-ui-field mw-title-field left"
              value="<?php print $data['title']; ?>" />


          <input type="hidden" name="is_active" id="is_post_active" value="<?php print $data['is_active']; ?>" />
          <div class="mw-ui-btn-nav mw-ui-btn-nav-post-state" id="un-or-published">
             <span data-val="n" class="<?php if($data['is_active'] == 'n'): ?> active<?php endif; ?>"><span class="ico iRemove"></span>Unpublished</span><span data-val="y" class="<?php if($data['is_active'] != 'n'): ?> active<?php endif; ?>"><span class="ico itabpublished"></span>Published</span>
          </div>



    </div>

	<div class="mw-ui-field-holder" style="padding: 0 0 1px;">
		<div class="edit-post-url"><span class="view-post-site-url"><?php print site_url(); ?></span><span  style="max-width: 160px; overflow: hidden; text-overflow: ellipsis; " class="view-post-slug active" onclick="mw.slug.toggleEdit()"><?php print ($data['url'])?></span>
			<input  style="width: 160px;" name="content_url" class="edit-post-slug"  onblur="mw.slug.toggleEdit();mw.slug.setVal(this);" type="text" value="<?php print ($data['url'])?>" />
			<span class="edit-url-ico" onclick="mw.slug.toggleEdit()"></span>
        </div>
        <?php if($data['content_type'] == 'page'){ ?>
          <div class="quick-parent-selector">
             <module type="content/selector" no-parent-title="No parent page" field-name="parent_id_selector" change-field="parent" selected-id="<?php print $data['parent']; ?>"  remove_ids="<?php print $data['id']; ?>" recommended-id="<?php print $recommended_parent; ?>"   />
          </div>
        <?php } ?>

	</div>
	<?php if($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
	<div class="mw-ui-field-holder" style="padding-top: 0">
    	<div class="mw-ui-field mw-tag-selector mw-ui-field-dropdown mw-ui-field-full" id="mw-post-added-<?php print $rand; ?>">
    		<input type="text" class="mw-ui-invisible-field" placeholder="<?php _e("Click here to add to categories and pages"); ?>." style="width: 280px;" id="quick-tag-field" />
    	</div>
    	<div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector" id="mw-category-selector-<?php print $rand; ?>" >
    		<?php if($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
    		<module
                    type="categories/selector"
                    for="content"
        			active_ids="<?php print $data['parent']; ?>"
        			subtype="<?php print $data['subtype']; ?>"
        			categories_active_ids="<?php print $categories_active_ids; ?>"
        			for-id="<?php print $data['id']; ?>" />
    		<?php endif; ?>
    	</div>
	</div>
	<?php endif; ?>
	<?php if($data['content_type'] == 'post' or $data['subtype'] == 'post' or $data['subtype'] == 'product'): ?>
	<div class="mw-ui-field-holder">
		<textarea class="semi_hidden" name="content" id="quick_content_<?php print $rand ?>"></textarea>
	</div>
	<?php endif; ?>
	<?php if($data['content_type'] == 'page'):  ?>
	<module type="content/layout_selector" id="mw-quick-add-choose-layout" autoload="yes" template-selector-position="bottom" content-id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" />
	<?php endif; ?>

    <ul class="quick-add-nav" id="quick-add-post-options">
        <li class="active"><span><span class="ico itabpic"></span><span><?php _e("Picture Gallery"); ?></span></span></li>
        <?php if($data['content_type'] == 'page'): ?> <li><span><span class="ico itabaddtonav"></span><span><?php _e('Add to navigation menu'); ?></span> </span></li><?php endif; ?>
        <?php  if(trim($data['subtype']) == 'product'): ?>
          <li><span><span class="ico itabprice"></span><span><?php _e("Price & Fields"); ?></span></span></li>
          <li><span><span class="ico itabtruck"></span><span><?php _e("Shipping & Options"); ?></span></span></li>
        <?php else: ?>
          <li><span><span class="ico itabcustoms"></span><span><?php _e("Custom Fields"); ?></span></span></li>
        <?php endif; ?>
        <li><span><span class="ico itabadvanced"></span><span><?php _e("Advanced"); ?></span></span></li>
    </ul>
    <div class="mw-o-box">
      <div class="mw-o-box-content">
          <div class="quick-add-post-options-item" style="display: block">
             <microweber module="pictures/admin" for="content" for-id=<?php print $data['id']; ?> />
             <?php event_trigger('mw_admin_edit_page_after_pictures', $data); ?>
          </div>
        <?php if($data['content_type'] == 'page'): ?>
            <div class="quick-add-post-options-item">
               <?php event_trigger('mw_edit_page_admin_menus', $data); ?>
               <?php event_trigger('mw_admin_edit_page_after_menus', $data); ?>
            </div>
        <?php endif; ?>
            <div class="quick-add-post-options-item">
                <module
                    type="custom_fields/admin"
                    <?php if( trim($data['subtype']) == 'product' ): ?> default-fields="price" <?php endif; ?>
                    content-id="<?php print $data['id'] ?>"
                    id="fields_for_post_<?php print $rand; ?>" 	 />
            </div>
        <?php  if(trim($data['subtype']) == 'product'): ?>
            <div class="quick-add-post-options-item">
                <?php event_trigger('mw_edit_product_admin', $data); ?>
            </div>
        <?php endif; ?>

        <div class="quick-add-post-options-item" id="quick-add-post-options-item-advanced">
             <module type="content/advanced_settings" content-id="<?php print $data['id']; ?>"  content-type="<?php print $data['content_type']; ?>" subtype="<?php print $data['subtype']; ?>"    />
        </div>
      </div>
    </div>

    <div class="mw-ui-field-holder">
    	<?php if($is_live_edit == false) : ?>
        <div class="post-save-and-go-live">
        	<button type="submit" class="mw-ui-btn mw-ui-btn-green right">Save</button>
        	<button type="button" class="mw-ui-btn go-live" onclick="mw.edit_content.handle_form_submit(true);" data-text="<?php _e("Go Live Edit"); ?>"><?php _e("Go Live Edit"); ?></button>
        </div>
    	<?php else: ?>
    	<span class="mw-ui-btn go-live right mw-ui-btn-green" onclick="mw.edit_content.handle_form_submit(true);" data-text="<?php _e("Go Live Edit"); ?>"><?php _e("Save"); ?></span>
        <?php endif; ?>
	</div>

	<?php event_trigger('mw_admin_edit_page_footer', $data); ?>
 </form>
  <div class="quick_done_alert" style="display: none">
  	<h2><span style="text-transform: capitalize"><?php print $data['subtype'] ?></span> has been created.</h2>
  	<a href="javascript:;" class="mw-ui-link">Go to <?php print $data['subtype'] ?></a>
      <span class="mw-ui-btn" onclick="$(mw.tools.firstParentWithClass(this, 'mw-inline-modal')).remove();">Create New</span>
  </div>
<script>
    mw.require("content.js");
    mw.require("files.js");
</script> 
<script>
/* FUNCTIONS */



mw.edit_content = {};

mw.edit_content.saving = false;

mw.edit_content.load_editor  = function(element_id){
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
        editor.style.height = "300px";
	 }
	 var layout_selector =  mw.$('#mw-quick-add-choose-layout')
     if(layout_selector !== null){
       layout_selector.attr('inherit_from',parent_page);
       mw.reload_module('#mw-quick-add-choose-layout');
     }
}
mw.edit_content.before_save = function(){
	mw.askusertostay=false;
	if(window.parent != undefined && window.parent.mw != undefined){
		window.parent.mw.askusertostay=false;
	}
}
mw.edit_content.after_save = function(){
	mw.askusertostay=false;
	var content_id =  mw.$('#mw-content-id-value').val();
	if(content_id == 0){
	    mw.reload_module('#<?php print $module_id ?>');
 	}
	if(parent !== self && !!parent.mw){
        parent.mw.reload_module('posts');
        parent.mw.reload_module('shop/products');
        parent.mw.reload_module('content');
    	parent.mw.reload_module('pages');
    	parent.mw.askusertostay=false;
    	<?php if($is_current!=false) :  ?>
    	if(window.parent.mw.history != undefined){
    		window.parent.mw.history.load('latest_content_edit');
    	}
    	<?php endif; ?>
	}
	mw.reload_module('[data-type="pages"]', function(){
        if( mw.$("#pages_tree_toolbar .mw_del_tree_content").length === 0 ){
            mw.$("#pages_tree_toolbar").removeClass("activated");
            mw.treeRenderer.appendUI('#pages_tree_toolbar');
            mw.tools.tree.recall(mwd.querySelector('.mw_pages_posts_tree'));
        }
     });
}

mw.edit_content.set_category = function(id){
      /* FILLING UP THE HIDDEN FIELDS as you change category or parent page */

	  var names = [];
      var inputs = mwd.getElementById(id).querySelectorAll('input[type="checkbox"]'), i=0, l = inputs.length;
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
      var inputs = mwd.getElementById(id).querySelectorAll('input[type="radio"]'), i=0, l = inputs.length;
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
	
mw.edit_content.render_category_tree = function(id){
    if(mw.treeRenderer != undefined){
    	   mw.treeRenderer.appendUI('#mw-category-selector-'+id);
    	   mw.tools.tag({
    		  tagholder:'#mw-post-added-'+id,
    		  items: ".mw-ui-check",
    		  itemsWrapper: mwd.querySelector('#mw-category-selector-'+id),
    		  method:'parse',
    		  onTag:function(){
    			 mw.edit_content.set_category('mw-category-selector-'+id)
    		  },
    		  onUntag:function(){
    			 mw.edit_content.set_category('mw-category-selector-'+id)
    		  }
    	  });
    }
}

mw.edit_content.handle_form_submit = function(go_live){
        if(mw.edit_content.saving){ return false; }
        mw.edit_content.saving = true;
		var el = this;
		var go_live_edit = go_live || false;
		var el = mwd.getElementById('quickform-<?php print $rand; ?>');
		if(el === null){
		    return;
		}
		mw.edit_content.before_save();
        var module =  $(mw.tools.firstParentWithClass(el, 'module'));
        var data = mw.serializeFields(el);
        module.addClass('loading');
        mw.content.save(data, {
          onSuccess:function(){
             if(parent !== self && !!window.parent.mw){
        		window.parent.mw.askusertostay=false;
        	 }
			if(go_live_edit != false){
				   $.get('<?php print site_url('api_html/content_link/?id=') ?>'+this, function(data) {
					 window.top.location.href = data+'/editmode:y';
				   });
			}
            else {
    			 mw.$("#<?php print $module_id ?>").attr("content-id",this);
    			 mw.$("#<?php print $module_id ?>").attr("just-saved",this);
    			 mw.edit_content.after_save();
			}
			mw.edit_content.saving = false;
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
              mw.edit_content.saving = false;
          }
        });
}
/* END OF FUNCTIONS */	

</script>
<script>
    $(document).ready(function(){
       mw.edit_content.load_editor();
       <?php if($just_saved!=false) : ?>
       mw.$("#<?php print $module_id ?>").removeAttr("just-saved");
       <?php endif; ?>
       mw.edit_content.render_category_tree("<?php print $rand; ?>");

        mw.$("#quickform-<?php print $rand; ?>").submit(function(){
          mw.edit_content.handle_form_submit();
          return false;
        });

		/* reloading the editor on parent change */
       mw.$('#mw-parent-page-value').bind('change', function(e){
          mw.edit_content.load_editor();
       });
       mw.tools.tabGroup({
          nav: mw.$("#quick-add-post-options li"),
          tabs: mw.$(".quick-add-post-options-item")
       });
       var piblished_nav = mwd.getElementById("un-or-published");
       mw.ui.btn.radionav(piblished_nav, 'span');
       $(piblished_nav.getElementsByTagName('span')).bind("click", function(){
            if($(this).hasClass("active")){
                mw.$("#is_post_active").val($(this).dataset("val"));
            }
       });

    });
</script>



