<?php
$form_rand_id  = uniqid().rand();
if(!isset($params["data-category-id"])){
	$params["data-category-id"] = CATEGORY_ID;
}

$data = get_category_by_id($params["data-category-id"]);

if($data == false or empty($data )){
    include('_empty_category_data.php');
}


$just_saved = false;

if(isset($params['just-saved'])){
    $just_saved = $params['just-saved'];
  }

?>
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
		 mw.category_is_saving = true;
		 $('.mw-cat-save-submit').addClass('disabled');
         mw.tools.addClass(mw.tools.firstParentWithClass(this, 'module'), 'loading');
         mw.form.post(mw.$('#admin_edit_category_form_<?php print $form_rand_id ?>') , '<?php print site_url('api/category/save') ?>', function(){
        	  mw.notification.success("Category changes are saved");
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
        	 	mw.url.windowHashParam("action", "editcategory:" + this);
             <?php endif; ?>


             var module = mw.tools.firstParentWithClass(form, 'module');



             <?php if(isset($data['quick_edit'])){ ?>


             $(module).attr("just-saved", true);

             mw.reload_module(module);

             <?php } else { ?>

              mw.tools.removeClass(module, 'loading');

             <?php } ?>

	    });
        mw.category_is_saving = false;
        $('.mw-cat-save-submit').removeClass('disabled');
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
	  }
      else if(isset($params['page-id'])){
		  $data['rel_id'] = intval($params['page-id']);
	  }
    }

  ?>


  <?php if($just_saved!=false) : ?>


  <div class="quick-post-done">
    <h2>Well done, you have saved your changes. </h2>
    <label class="mw-ui-label"><small>Go to see them at this link</small></label>
    <div class="vSpace"></div>
    <a target="_top" class="quick-post-done-link" href="<?php print content_link($data['id']); ?>?editmode=y"><?php print content_link($data['id']); ?></a>
    <div class="vSpace"></div>
    <label class="mw-ui-label"><small>Or create new content again</small></label>
    <div class="vSpace"></div>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-green" onclick="mw.reload_module('content/quick')">Create New</a>
  </div>





<?php endif; ?>



<pre><?php var_dump($data); ?>   </pre>

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
		<input  class="mw-ui-field mw-title-field" name="title" type="text" placeholder="<?php _e("Sub-category Name"); ?>" />
		<?php else: ?>
		<?php if( isset($data['parent_id'] ) and $data['parent_id'] > 0): ?>
		<span class="mw-title-field-label mw-title-field-label-subcat"></span>
		<?php else: ?>
		<span class="mw-title-field-label mw-title-field-label-category"></span>
		<?php endif; ?>
		<input  class="mw-ui-field mw-title-field" name="title" type="text" <?php if($data['id'] == 0){ ?>placeholder<?php } else{ ?>value<?php } ?>="<?php print ($data['title']); ?>" />
		<?php endif; ?>
	</div>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label">
			<?php _e("Parent"); ?>
		</label>
		<?php  $is_shop = '';    ?>
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
	<input name="position"  type="hidden" value="<?php print ($data['position'])?>" />
	<input type="submit" class="semi hidden" name="save" />
	<div class="post-save-and-go-live">
		<button type="button" onclick="save_cat(this);" class="mw-ui-btn mw-ui-btn-green"><?php _e("Save"); ?></button>
    </div>
	<div class="vSpace"></div>
	<div class="advanced_settings">
       <ul id="quick-add-post-options" class="quick-add-nav">
          <li><span><span class="ico itabpic"></span><span>Picture Gallery</span></span></li>
          <li><span><span class="ico itabadvanced"></span><span>Advanced</span></span></li>
       </ul>
       <div class="mw-o-box mw-o-box-content quick-add-post-options-item">
            <div class="pictures-editor-holder">
    			<module type="pictures/admin" for="categories" for-id=<?php print $data['id'] ?>  />
    		</div>
       </div>
       <div class="mw-o-box mw-o-box-content quick-add-post-options-item">
           <div class="mw-ui-field-holder">
        		<label class="mw-ui-label">
        			<?php _e("Description"); ?>
        		</label>
        		<textarea  class="mw-ui-field" name="description"><?php print ($data['description'])?></textarea>
        	</div>
            <div class="mw-ui-field-holder">
    			<?php if(!isset($data['users_can_create_content'])) { $data['users_can_create_content'] = 'n'; } 	?>
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
       </div>
	</div>
</form>
