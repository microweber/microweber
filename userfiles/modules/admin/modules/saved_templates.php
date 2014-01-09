<?php only_admin_access(); ?>
<?php if(isset($params['module_name']) and isset($params['module_id'])): ?>
<?php


 $module_name = $params['module_name'];
 $module_id = $params['module_id'];

  ?>
<script  type="text/javascript">



$(document).ready(function(){

	 mw.$('.module-templates-action-btn').click(function() {
	
	var temp_form1 = mw.tools.firstParentWithClass(this, 'module-templates-add-new-holder');
	var save_module_as_template_url = '<?php print site_url('api') ?>/save_module_as_template';
	var is_del = $(this).attr('delete');
	if(is_del != undefined){
		var save_module_as_template_url = '<?php print site_url('api') ?>/delete_module_as_template';
	}
	

	var is_use = $(this).attr('use');
	
	
		var is_release = $(this).attr('release');
	if(is_release != undefined){
		var $old_od = mw.$('#'+is_release).attr("old-id")
		d($old_od);
		d('#<?php print $module_id ?>');
	}
	
	
	if(is_use != undefined){
		 
		 
		/*if (parent.mw != undefined) {
                                if (parent.mw.reload_module !== undefined) {
									
 									parent.mw.$('#<?php print $module_id ?>').attr("id",is_use);
 									 parent.mw.reload_module("#"+is_use);
									
                                }
                            }*/
						if (mw.reload_module != undefined) {	
						mw.$('#<?php print $module_id ?>').attr("old-id",is_use);
				 mw.$('#<?php print $module_id ?>').attr("id",is_use);
				 mw.reload_module("#"+is_use);
				 
				 
				 var menu = mwd.getElementById('module-saved-templates');
				 if(mw.tools.hasParentsWithClass(menu, 'mw_modal')){
					var modal = mw.tools.firstParentWithClass(menu, 'mw_modal');
					var frame = modal.getElementsByTagName('iframe')[0];
					var form = $(frame).contents().find("#mw_reload_this_module_popup_form")[0];
					form.elements['id'].value = is_use;	
						 $(form).submit(); 
				 }
				 
				 
				 
				 
				 
				 
				 
								//	 mw.reload_module("<?php print $module_name  ?>");			
						}
							
							
		 
		 
		 
	} else {
		 
		 mw.form.post(temp_form1 ,save_module_as_template_url , function(){
		 mw.reload_module("#<?php print $params['id'] ?>");
		 });
	 
	}
	 
	 
	 
     return false;
 	});

  });


	   
 
</script>
<?php $fffound = false; ?>
<div id="module-saved-templates">
<h5><?php _e("Templates"); ?></h5>
  <?php $saved_modules = get_saved_modules_as_template("module={$module_name}"); ?>
  <?php if(is_array($saved_modules )): ?>
  <ul>
    <?php foreach($saved_modules  as $item): ?>
    <li>
      <div class="module-templates-add-new-holder">
        <input type="hidden" name="id" value="<?php print  $item['id'] ?>">

        <input type="hidden" name="module" value="<?php print  $item['module'] ?>">
      <?php print  $item['name'] ?>
        <input  type="hidden" name="name" value="<?php print  $item['name'] ?>">

        <input  type="hidden" name="module_id" value="<?php print  $item['module_id'] ?>">
        <?php if($item['module_id'] == $module_id) : ?>
<!--        <input  type="button" value="release"   release="<?php print  $item['module_id'] ?>" class="module-templates-action-btn"   /><?php print  $item['module_id'] ?>
-->        <?php else : ?>
<?php endif; ?>
<?php if($item['module_id'] == $module_id){
	$fffound = 1; 
	
}

?>

<span delete="1" class="mw-close module-templates-action-btn"></span>

<span use="<?php print  $item['module_id'] ?>" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue module-templates-action-btn right" >Use</span>





      </div>
    </li>
    <?php endforeach ; ?>
  </ul>
  <?php endif; ?>
</div>
<?php if(($fffound ) == false): ?>
<div class="module-templates-add-new-holder">
  <input type="hidden" name="module" value="<?php print $module_name ?>">
  <input  type="text" name="name" value="" class="mw-ui-field" onfocus="setVisible(event);" onblur="setVisible(event);">
  <input  type="hidden" name="module_id" value="<?php print $module_id ?>">
  <input  type="button" value="Save template"  class="mw-ui-btn module-templates-action-btn"   />
</div>
  <?php endif; ?>
<?php else : ?>
error $params['module_name'] is not set or $params['module_id'] is not set
<?php endif; ?>
