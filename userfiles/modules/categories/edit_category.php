<?php
only_admin_access();
$form_rand_id  = uniqid().rand();
$data = false;
if(isset($params["data-category-id"])){
	$data = get_category_by_id($params["data-category-id"]);
}



if($data == false or empty($data )){
    include('_empty_category_data.php');
}


$just_saved = false;
$quick_edit = false;
if(isset($params['just-saved'])){
    $just_saved = $params['just-saved'];
}
if(isset($params['quick_edit'])){
    $quick_edit = $params['quick_edit'];
}
 
?>
<?php
 
 $wrapper_class = 'mw-edit-category-item-admin'; 
 
  if(isset($params['live_edit'])){
	$wrapper_class = 'module-live-edit-settings'; 
	 
 }?>

<div class="<?php print $wrapper_class; ?>"> 
  <script  type="text/javascript">
    mw.require('forms.js');
</script> 
  <script  type="text/javascript">
 function set_category_parent_<?php print $form_rand_id ?>(){
	 var sel = mw.$('#edit_category_set_par_<?php print $form_rand_id; ?> input:checked').parents('li').first(),
	     is_cat = sel.attr("data-category-id"),
	     is_page = sel.attr("data-page-id");

	 if(typeof is_cat !== "undefined"){
        mw.$('#rel_id_<?php print $form_rand_id ?>').val(0);
        mw.$('#parent_id_<?php print $form_rand_id ?>').val(is_cat);
     }
     if(typeof is_page !== "undefined"){
        mw.$('#rel_id_<?php print $form_rand_id ?>').val(is_page);
        mw.$('#parent_id_<?php print $form_rand_id ?>').val(0);
     }

 }
  function onload_set_parent_<?php print $form_rand_id ?>(){
	     var tti = mw.$('#rel_id_<?php print $form_rand_id ?>').val();
		 var par_cat   = mw.$('#parent_id_<?php print $form_rand_id ?>').val();
		 if(par_cat != undefined && parseFloat(par_cat) > 0 ){
		    var tree =  mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>');
            var li =  tree.querySelector('li[data-category-id="'+par_cat+'"]');
            var radio = li.querySelector('input[type="radio"]');
            radio.checked = true;

		 }
         else  if(tti != undefined && parseFloat(tti) > 0 ){
            var tree =  mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>');
            var li =  tree.querySelector('li[data-page-id="'+tti+'"]');
            var radio = li.querySelector('input[type="radio"]');
            radio.checked = true;
         }
  }

  save_cat = function(el){
    if(mwd.querySelector('.mw-ui-category-selector input:checked') !== null){
       $(document.forms['admin_edit_category_form_<?php print $form_rand_id ?>']).submit();
    }
    else{
      Alert('<?php _e("Please choose Page or Category"); ?>.');
    }
  }

  make_new_cat_after_save = function(el){
        $('#<?php print $params['id'] ?>').removeClass('loading');
        $('#<?php print $params['id'] ?>').removeAttr('just-saved');
        $('#<?php print $params['id'] ?>').removeAttr('selected-category-id');
        $('#<?php print $params['id'] ?>').removeAttr('data-category-id');
        $('#<?php print $params['id'] ?>').removeAttr('category-id');
        <?php if(isset($params['live_edit']) != false) : ?>
        window.location.reload();
        <?php else: ?>
        mw.reload_module('#<?php print $params['id'] ?>');
        <?php endif; ?>
  }
  
  continue_editing_cat = function(){
		 mw.$('.add-edit-category-form').show();
		 mw.$('.mw-quick-cat-done').hide();
  }
   
 <?php if($just_saved != false) : ?>
	  $('#<?php print $params['id'] ?>').removeClass('loading');
	   $('#<?php print $params['id'] ?>').removeAttr('just-saved');

	 <?php endif; ?>
$(document).ready(function(){

	mw.category_is_saving = false;
	<?php if(intval($data['id']) == 0): ?>
    <?php endif; ?>
     var h = mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>');
	  mw.$('label', h).click(function() {
  	     set_category_parent_<?php print $form_rand_id ?>();
      });
	 mw.$('#admin_edit_category_form_<?php print $form_rand_id ?>').submit(function() {
	     var form = this;
		 if(mw.category_is_saving){
			 return false;
		 }
		 mw.notification.success("Saving...",3000);
		 mw.category_is_saving = true;
		 $('.mw-cat-save-submit').addClass('disabled');
         mw.tools.addClass(mw.tools.firstParentWithClass(this, 'module'), 'loading');
         mw.form.post(mw.$('#admin_edit_category_form_<?php print $form_rand_id ?>') , '<?php print api_link('category/save') ?>', function(val){
			 
			 
			 if(typeof(this.error) != "undefined"){
				  mw.notification.msg(this);
				   mw.category_is_saving = false;
				  return false;
			 }
			 
			 
			 
            mw.$('#mw-notifications-holder').empty();
        	  mw.notification.success("Category changes are saved");
 			  var v = this.toString();
			  mw.$('#mw_admin_edit_cat_id').val(v);
			  mw.$('#mw-cat-pics-admin').attr("for-id",v);
        	  mw.reload_module('[data-type="categories"]');
        	   if(self !== parent && !!parent.mw){
                parent.mw.reload_module('categories');
        	  }
        	  mw.reload_module('[data-type="categories/manage"]');
              mw.$('[data-type="pages"]').removeClass("activated");
        	  mw.reload_module('[data-type="pages"]', function(){
        	    mw.treeRenderer.appendUI('[data-type="pages"]');
                mw.tools.tree.recall(mwd.querySelector("#pages_tree_toolbar").parentNode);
        	  });
        	  <?php if(intval($data['id']) == 0): ?>
        	  	mw.url.windowHashParam("new_content", "true");
				
        	  //	mw.url.windowHashParam("action", "editcategory:" + this);

				
             <?php endif; ?>
			 mw.reload_module('#<?php print $params['id'] ?>');

             var module = mw.tools.firstParentWithClass(form, 'module');
				mw.tools.removeClass(module, 'loading');
			    mw.category_is_saving = false;
                mw.$('.mw-cat-save-submit').removeClass('disabled');
				
			 
				
				
				
	    });

    return false;
 });

 mw.tools.tabGroup({
    nav: mw.$("#quick-add-post-options li"),
    tabs: mw.$(".quick-add-post-options-item"),
    toggle:true
 });


});
</script>
  <?php

    if(intval($data['id']) == 0){
	  if(isset($params['selected-category-id']) and intval($params['selected-category-id']) != 0){
		  $data['parent_id'] = intval($params['selected-category-id']);
	  }  else if(isset($params['recommended_parent'])){
		  $data['rel_id'] = intval($params['recommended_parent']);
	  }
      else if(isset($params['page-id'])){
		  $data['rel_id'] = intval($params['page-id']);
	  }
    }

?>
  <form class="add-edit-category-form" id="admin_edit_category_form_<?php print $form_rand_id ?>" name="admin_edit_category_form_<?php print $form_rand_id ?>" autocomplete="off" style="<?php if($just_saved != false) { ?> display: none; <?php } ?>">
    <input name="id" type="hidden" id="mw_admin_edit_cat_id" value="<?php print ($data['id'])?>" />
    <input name="table" type="hidden" value="categories" />
    <input name="rel" type="hidden" value="<?php print ($data['rel_type'])?>" />
    <input name="rel_id" type="hidden" value="<?php print ($data['rel_id'])?>" id="rel_id_<?php print $form_rand_id ?>"  />
    <input name="data_type" type="hidden" value="<?php print ($data['data_type'])?>" />
    <input name="parent_id" type="hidden" value="<?php print ($data['parent_id'])?>" id="parent_id_<?php print $form_rand_id ?>" />
    <div class="mw-ui-field-holder">
      <div class="mw-ui-row-nodrop valign" id="content-title-field-row">
        <div class="mw-ui-col" style="width: 30px;"> <span class="mw-icon-category admin-manage-toolbar-title-icon"></span> </div>
        <div class="mw-ui-col">
          <?php if($data['id'] == 0 and isset($data['parent_id'] ) and $data['parent_id'] >0): ?>
          <span class="mw-title-field-label mw-title-field-label-subcat"></span>
          <input id="content-title-field" class="mw-ui-invisible-field mw-ui-field-big" name="title" type="text" placeholder="<?php _e("Sub-category Name"); ?>" />
          <?php else: ?>
          <?php if( isset($data['parent_id'] ) and $data['parent_id'] > 0): ?>
          <span class="mw-title-field-label mw-title-field-label-subcat"></span>
          <?php else: ?>
          <span class="mw-title-field-label mw-title-field-label-category"></span>
          <?php endif; ?>
          <input  class="mw-ui-invisible-field mw-ui-field-big" id="content-title-field" name="title" type="text" <?php if($data['id'] == 0){ ?>placeholder<?php } else{ ?>value<?php } ?>="<?php print ($data['title']); ?>" />
          <?php endif; ?>
        </div>
        <div class="mw-ui-col" id="content-title-field-buttons">
          <div class="content-title-field-buttons">
            <?php if(intval($data['id']) != 0): ?>
            <div class="mw-ui-btn-nav pull-right" style="margin-left: 20px;"> <a href="<?php print admin_url(); ?>view:content#action=new:category" target="_top" class="mw-ui-btn tip" data-tip="<?php _e("Add new category"); ?>"> <span class="mw-icon-plus"></span> <span class="mw-icon-category"></span> </a> <a href="javascript:mw.tools.tree.del_category('<?php print ($data['id'])?>');" class="mw-ui-btn mw-ui-btn-icon tip" data-tip="<?php _e("Delete category"); ?>"><span class="mw-icon-bin" style="font-size: 22px;"></span></a> </div>
            <?php endif; ?>
            <button type="button" onclick="save_cat(this);" class="mw-ui-btn mw-ui-btn-invert pull-right" id="mw-admin-cat-save">
            <?php _e("Save"); ?>
            </button>
          </div>
        </div>
        <script>mw.admin.titleColumnNavWidth();</script> 
      </div>
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">
        <?php _e("Select Parent page or category"); ?>
      </label>
      <?php  $is_shop = '';    ?>
      <div class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector" style="display: block" id="edit_category_set_par_<?php print $form_rand_id ?>">
        <module  type="categories/selector" include_inactive="true" categories_active_ids="<?php print (intval($data['parent_id']))?>" active_ids="<?php print ($data['rel_id'])?>" <?php print $is_shop ?> input-name="temp_<?php print $form_rand_id ?>" input-name-categories='temp_<?php print $form_rand_id ?>' input-type-categories="radio" categories_removed_ids="<?php print (intval($data['id']))?>"  show_edit_categories_admin_link="true"  />
      </div>
    </div>
    <script type="text/javascript">
    $(mwd).ready(function(){
        mw.treeRenderer.appendUI('#edit_category_set_par_<?php print $form_rand_id ?>');
        mw.tools.tree.openAll(mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>'));
		 
		 var _parent = mwd.querySelector('#edit_category_set_par_<?php print $form_rand_id ?> input:checked');
		 
		 if(_parent !== null){
			 var plabel = mw.tools.firstParentWithClass(_parent, 'mw-ui-check');
			plabel.scrollIntoView(false);
			mw.tools.highlight(plabel);
			 
		}

				   
				   

        mw.tabs({
            nav:"#tabsnav .mw-ui-btn",
            tabs:".quick-add-post-options-item",
            toggle:true
        });
    });
  </script>
    <input name="position"  type="hidden" value="<?php print ($data['position'])?>" />
    <div class="advanced_settings">
      <label class="mw-ui-label">
        <?php _e("Category images and settings"); ?>
      </label>
      <div class="mw-ui-btn-nav" id="tabsnav"> <span class="mw-ui-btn"><span class="mw-icon-picture"></span><span>
        <?php _e("Picture Gallery"); ?>
        </span></span> <span class="mw-ui-btn"><span class="mw-icon-gear"></span><span>
        <?php _e("Advanced"); ?>
        </span></span> </div>
      <div class="mw-ui-box mw-ui-box-content quick-add-post-options-item">
        <div class="pictures-editor-holder">
          <module type="pictures/admin" for="categories" for-id="<?php print $data['id'] ?>"  id="mw-cat-pics-admin" />
        </div>
      </div>
      <div class="mw-ui-box mw-ui-box-content quick-add-post-options-item">
        <?php if (isset($data['id']) and $data['id'] != 0): ?>
        <div class="mw-ui-field-holder">
          <label class="mw-ui-label">
            <?php _e("Link"); ?>
          </label>
          <small> <a href="<?php print category_link($data['id']); ?>" target="_blank"><?php print category_link($data['id']); ?></a> </small> </div>
        <?php endif; ?>
        <div class="mw-ui-field-holder">
          <label class="mw-ui-label">
            <?php _e("Slug"); ?>
          </label>
          <input type="text"  class="mw-ui-field w100" name="url" value="<?php (isset($data['url'])) ? print ($data['url']): ''?>" />
        </div>
        <div class="mw-ui-field-holder">
          <label class="mw-ui-label">
            <?php _e("Description"); ?>
          </label>
          <textarea  class="mw-ui-field w100" name="description"><?php print ($data['description'])?></textarea>
        </div>
        <?php if (isset($data['id'])): ?>
        <module type="content/views/settings_from_template" content-type="category" category-id="<?php print $data['id'] ?>"  />
        <?php endif; ?>
        <div><small><a  href="javascript:$('.mw-edit-cat-edit-mote-advanced-toggle').toggle();void(0);"> <span class="mw-icon-more" style="opacity:0.3"></span> </a></small> </div>
        <div class="mw-edit-cat-edit-mote-advanced-toggle" style="display:none">
          <div class="mw-ui-field-holder">
            <?php if(!isset($data['users_can_create_content'])) { $data['users_can_create_content'] = 0; } 	?>
            <div class="mw-ui-check-selector">
              <div class="mw-ui-label left" style="width: 230px">
                <?php _e("Can users create content"); ?>
                <small class="mw-help" data-help="If you set this to YES the website users will be able to add content under this category">(?)</small></div>
              <label class="mw-ui-check">
                <input name="users_can_create_content" type="radio"  value="0" <?php if( '' == trim($data['users_can_create_content']) or '0' == trim($data['users_can_create_content'])): ?>   checked="checked"  <?php endif; ?> />
                <span></span><span>
                <?php _e("No"); ?>
                </span></label>
              <label class="mw-ui-check">
                <input name="users_can_create_content" type="radio"  value="1" <?php if( '1' == trim($data['users_can_create_content'])): ?>   checked="checked"  <?php endif; ?> />
                <span></span><span>
                <?php _e("Yes"); ?>
                </span></label>
            </div>
          </div>
          <?php if (isset($data['id'])): ?>
          <?php if(!isset($data['category_subtype'])) { $data['category_subtype'] = 'default'; } 	?>
          <input type="hidden" name="category_subtype" value="<?php print $data['category_subtype'] ?>" />
          <script  type="text/javascript">

			$(document).ready(function(){
			
				mw.dropdown();
			
			
			$('.edit-category-choose-subtype-dd').on('change',function(){
				var val = $(this).getDropdownValue();
				$('[name="category_subtype"]','#admin_edit_category_form_<?php print $form_rand_id ?>').val(val)
				
				$('#admin_edit_category_subtype_settings_<?php print $form_rand_id ?>').attr('category_subtype',val);
				mw.reload_module('#admin_edit_category_subtype_settings_<?php print $form_rand_id ?>');
				
				});
			});
			
			
			
			</script>
          <div class="mw-ui-field-holder">
            <div class="mw-ui-label" style="width: 230px">
              <?php _e("Category"); ?>
              <?php _e("Subtype"); ?>
              <small class="mw-help" data-help="You can set the category behaviour by changing its subtype">(?)</small></div>
            <div class="mw-dropdown mw-dropdown-default edit-category-choose-subtype-dd"> <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-small mw-dropdown-val"> <?php print ucwords($data['category_subtype']); ?> </span>
              <div class="mw-dropdown-content" style="display: none;">
                <ul>
                  <li value="default">Default</li>
                  <li value="content_filter">Content filter</li>
                </ul>
              </div>
            </div>
          </div>
          <module type="categories/edit_category_subtype_settings" category_subtype="<?php print $data['category_subtype'] ?>" category-id="<?php print $data['id'] ?>" id="admin_edit_category_subtype_settings_<?php print $form_rand_id ?>"  />
          <?php endif; ?>
        </div>
      </div>
    </div>
  </form>
</div>
