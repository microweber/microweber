<?php
$form_rand_id  = uniqid().rand();
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
 function set_category_parent_<?php print $form_rand_id ?>(){

	 $sel = mw.$('#edit_category_set_par_<?php print $form_rand_id ?> input:checked').parents('li').first();

	 is_cat = $sel.attr("data-category-id");
	 is_page = $sel.attr("data-page-id");

	 if(is_cat != undefined){
    	 mw.$('#rel_id_<?php print $form_rand_id ?>').val(0);
    	    mw.$('#parent_id_<?php print $form_rand_id ?>').val(is_cat);
    	 }
		 if(is_page != undefined){
		    mw.$('#rel_id_<?php print $form_rand_id ?>').val(is_page);
		    mw.$('#parent_id_<?php print $form_rand_id ?>').val(0);
         }

 }


  function onload_set_parent_<?php print $form_rand_id ?>(){
	   var tti = mw.$('#rel_id_<?php print $form_rand_id ?>').val();

		 var par_cat   = mw.$('#parent_id_<?php print $form_rand_id ?>').val();
		// mw.log(par_cat);
		 if(par_cat != undefined && parseFloat(par_cat) > 0 ){
		    var tree =  mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>');
            var li =  tree.querySelector('li[data-category-id="'+par_cat+'"]');
            var radio = li.querySelector('input[type="radio"]');
            radio.checked = true;

		 }  else  if(tti != undefined && parseFloat(tti) > 0 ){
                   var tree =  mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>');
            var li =  tree.querySelector('li[data-page-id="'+tti+'"]');
            var radio = li.querySelector('input[type="radio"]');
            radio.checked = true;

                }

  }

  save_cat = function(){

    if(mwd.querySelector('.mw-ui-category-selector input:checked') !== null){
       $(document.forms['admin_edit_category_form_<?php print $form_rand_id ?>']).submit();
    }

    else{
      Alert('<?php _e("Please choose Page or Category"); ?>.');
    }

  }


$(document).ready(function(){
	
	
	mw.category_is_saving = false;
	
	//
	 <?php if(intval($data['id']) == 0): ?>
	// onload_set_parent_<?php print $form_rand_id ?>();
	// set_category_parent_<?php print $form_rand_id ?>()
	 <?php endif; ?>




     var h = mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>');

	  mw.$('label', h).click(function() {
  	     set_category_parent_<?php print $form_rand_id ?>();
      });



	 mw.$('#admin_edit_category_form_<?php print $form_rand_id ?>').submit(function() {
		 
		 
		 
		 if(mw.category_is_saving == true){
			 return false;
		 }
		 
		 mw.category_is_saving = true;
		 $('.mw-cat-save-submit').addClass('disabled');
		 
  mw.notification.success("Saving category... Please wait...",10000);

 // set_category_parent_<?php print $form_rand_id ?>();
 mw.form.post(mw.$('#admin_edit_category_form_<?php print $form_rand_id ?>') , '<?php print site_url('api/save_category') ?>', function(){
	 
	 
	   mw.notification.success("Category changes are saved");
	 		

	 
	 mw.reload_module('[data-type="categories"]');
	 if(window.parent != undefined && window.parent.mw != undefined){
    window.parent.mw.reload_module('categories');
	
	 }
	 
	 
	 
	 mw.reload_module('[data-type="categories/manage"]');
     mw.$('[data-type="pages"]').removeClass("activated");
	  mw.reload_module('[data-type="pages"]', function(){
	    mw.treeRenderer.appendUI('[data-type="pages"]');
        mw.tools.tree.recall(mwd.querySelector("#pages_tree_toolbar").parentNode);
	  });
	  <?php if(intval($data['id']) == 0): ?>
	 	mw.url.windowHashParam("new_content", "true");
	 	mw.url.windowHashParam("action", "editcategory:" + this);
     <?php endif; ?>
	 });
    mw.category_is_saving = false;
    $('.mw-cat-save-submit').removeClass('disabled');
    return false;
 });
});
</script>
<?php if(intval($data['id']) == 0){
	  if(isset($params['selected-category-id']) and intval($params['selected-category-id']) != 0){
		  $data['parent_id'] = intval($params['selected-category-id']);
	  } elseif(isset($params['page-id'])){
		  $data['rel_id'] = intval($params['page-id']);
	  }

  }

  ?>
<?php  //d($params);?>

<form class="add-edit-page-post" id="admin_edit_category_form_<?php print $form_rand_id ?>" name="admin_edit_category_form_<?php print $form_rand_id ?>" autocomplete="Off">
	<input name="id" type="hidden" value="<?php print ($data['id'])?>" />
	<input name="table" type="hidden" value="categories" />
	<input name="rel" type="hidden" value="<?php print ($data['rel'])?>" />
	<input name="rel_id" type="hidden" value="<?php print ($data['rel_id'])?>" id="rel_id_<?php print $form_rand_id ?>"  />
	<input name="data_type" type="hidden" value="<?php print ($data['data_type'])?>" />
	<input name="parent_id" type="hidden" value="<?php print ($data['parent_id'])?>" id="parent_id_<?php print $form_rand_id ?>" />
	<div class="mw-ui-field-holder">
		<?php if($data['id'] == 0 and isset($data['parent_id'] ) and $data['parent_id'] >0): ?>
		<span class="mw-title-field-label mw-title-field-label-subcat"></span>
		<input  class="mw-ui-field mw-title-field" name="title" type="text" value="<?php _e("Sub-category Name"); ?>" />
		<?php else: ?>
		<?php if(isset($data['parent_id'] ) and $data['parent_id'] >0): ?>
		<span class="mw-title-field-label mw-title-field-label-subcat"></span>
		<?php else: ?>
		<span class="mw-title-field-label mw-title-field-label-category"></span>
		<?php endif; ?>
		<input  class="mw-ui-field mw-title-field" name="title" type="text" value="<?php print ($data['title'])?>" />
		<?php endif; ?>
	</div>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label">
			<?php _e("Parent"); ?>
		</label>
		<?php
      $is_shop = '';
    if (isset($params['is_shop'])) {
    	//$is_shop = '&is_shop=' . $params['is_shop'];
    }



       ?>
		<div class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector" style="display: block" id="edit_category_set_par_<?php print $form_rand_id ?>">
			<module  type="categories/selector"   categories_active_ids="<?php print (intval($data['parent_id']))?>" active_ids="<?php print ($data['rel_id'])?>" <?php print $is_shop ?> input-name="temp_<?php print $form_rand_id ?>" input-name-categories='temp_<?php print $form_rand_id ?>' input-type-categories="radio" categories_removed_ids="<?php print (intval($data['id']))?>"   />
		</div>
	</div>
	<script type="text/javascript">
    $(mwd).ready(function(){
        mw.treeRenderer.appendUI('#edit_category_set_par_<?php print $form_rand_id ?>');
        mw.tools.tree.openAll(mwd.getElementById('edit_category_set_par_<?php print $form_rand_id ?>'));
    });
  </script>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label">
			<?php _e("Description"); ?>
		</label>
		<textarea style="width: 600px;height: 50px;" class="mw-ui-field" name="description"><?php print ($data['description'])?></textarea>
	</div>
	<input name="position"  type="hidden" value="<?php print ($data['position'])?>" />
	<input type="submit" class="semi hidden" name="save" />




	<div class="post-save-bottom">
		<input type="submit" name="save" class="semi_hidden mw-cat-save-submit"    value="<?php _e("Save"); ?>" />
		<div class="vSpace"></div>
		<span style="min-width: 66px;" onclick="save_cat();" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green mw-cat-save-submit">
		<?php _e("Save"); ?>
		</span> </div>
	<div class="vSpace"></div>
	<div class="vSpace"></div>
	<div class="advanced_settings">
	<a href="javascript:;"  id="advanced-settings-toggler" onclick="mw.tools.toggle('.category_advanced_settings_holder', this);"   class="toggle_advanced_settings mw-ui-more">
	<?php _e('Advanced Settings'); ?>
	</a>
	 
	<div class="category_advanced_settings_holder" style="display:none">
		<div class="vSpace"></div>
		<div class="mw-ui-field-holder">
			<?php if(!isset($data['users_can_create_content'])) {
					$data['users_can_create_content'] = 'n';
				}
				
				
				?>
			<div class="mw-ui-check-selector">
				<div class="mw-ui-label left" style="width: 230px">
					<?php _e("Can users create content"); ?>
					<small class="mw-help" data-help="If you set this to YES the website users will be able to add content under this category">(?)</small></div>
				<label class="mw-ui-check">
					<input name="users_can_create_content" type="radio"  value="n" <?php if( '' == trim($data['users_can_create_content']) or 'n' == trim($data['users_can_create_content'])): ?>   checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("No"); ?>
					</span></label>
				<label class="mw-ui-check">
					<input name="users_can_create_content" type="radio"  value="y" <?php if( 'y' == trim($data['users_can_create_content'])): ?>   checked="checked"  <?php endif; ?> />
					<span></span><span>
					<?php _e("Yes"); ?>
					</span></label>
			</div>
		</div>
		<div class="clear vSpace"></div>
		<div class="pictures-editor-holder" >
			<module type="pictures/admin" for="categories" for-id=<?php print $data['id'] ?>  />
		</div>
	</div>
</form>
