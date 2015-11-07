<?php only_admin_access(); ?>
<?php if(isset($params['module_name']) and isset($params['module_id'])): ?>
<?php


 $module_name = $params['module_name'];
 $module_id = $params['module_id'];

  ?>
<script type="text/javascript">
  mw.require('forms.js', true);
</script> 
<script  type="text/javascript">

mw.module_preset_apply_actions_after_id_change = function (id){
	//d(window.parent.mw.$('#'+id))
window.parent.mw.reload_module("#"+id);	
}

$(document).ready(function(){

	 mw.$('.module-presets-action-btn').click(function() {
	
	var temp_form1 = mw.tools.firstParentWithClass(this, 'module-presets-add-new-holder');
	var save_module_as_template_url = '<?php print site_url('api') ?>/save_module_as_template';
	var is_del = $(this).attr('delete');
	if(is_del != undefined){
		var save_module_as_template_url = '<?php print site_url('api') ?>/delete_module_as_template';
	}

	var is_use = $(this).attr('use');
	
	var is_release = $(this).attr('release');
	if(is_release != undefined){
		var orig_id = window.parent.mw.$('#<?php print $module_id ?>').attr("data-module-original-id");
		 
		if(orig_id){
		 
		window.parent.mw.$('#<?php print $module_id ?>').attr("id",orig_id);
		mw.module_preset_apply_actions_after_id_change(orig_id);
		}
		//
	} else if(is_use != undefined){
		 
		 
		/*if (parent.mw != undefined) {
                                if (parent.mw.reload_module !== undefined) {
									
 									parent.mw.$('#<?php print $module_id ?>').attr("id",is_use);
 									 parent.mw.reload_module("#"+is_use);
									
                                }
                            }*/
						if (mw.reload_module != undefined) {	
							if(!window.parent.mw.$('#<?php print $module_id ?>').attr("data-module-original-id")){
							window.parent.mw.$('#<?php print $module_id ?>').attr("data-module-original-id",is_use);
							}
						 	window.parent.mw.$('#<?php print $module_id ?>').attr("module-id",is_use);
						 	//window.parent.mw.reload_module("#"+is_use);
			 		mw.module_preset_apply_actions_after_id_change('<?php print $module_id ?>');

				  
				 
								//	 mw.reload_module("<?php print $module_name  ?>");			
						}
							
							
		 
		 
		 
	} else {
		alert('zazazazaz <?php print addslashes( __FILE__); ?>');
		 mw.reload_module("#<?php print $module_id ?>")
		 window.parent.mw.form.post(temp_form1 ,save_module_as_template_url , function(){
		 window.parent.mw.reload_module("#<?php print $module_id ?>");
		 });
	 
	}
	 
	 
	 
     return false;
 	});

  });


	   
 
</script>
<?php $fffound = false; ?>
<div id="module-saved-presets">
<h5><?php _e("Preset"); ?> <?php print $module_id ?></h5>
<input  type="button" value="release"   release="<?php print  $module_id ?>" class="module-presets-action-btn"   />
  <?php $saved_modules = get_saved_modules_as_template("module={$module_name}"); ?>
  <?php if(is_array($saved_modules )): ?>
  <ul>
    <?php foreach($saved_modules  as $item): ?>
    <li>
      <div class="module-presets-add-new-holder">
        <input type="hidden" name="id" value="<?php print  $item['id'] ?>">

        <input type="hidden" name="module" value="<?php print  $item['module'] ?>">
       
        <input  type="text" class="" name="name" value="<?php print  $item['name'] ?>">

        <input  type="hidden" name="module_id" value="<?php print  $item['module_id'] ?>">
        <?php if($item['module_id'] == $module_id) : ?>
<!--        <input  type="button" value="release"   release="<?php print  $item['module_id'] ?>" class="module-presets-action-btn"   /><?php print  $item['module_id'] ?>
-->        <?php else : ?>
<?php endif; ?>
<?php if($item['module_id'] == $module_id){
	$fffound = 1; 
	
}

?>

<span delete="1" class="mw-close module-presets-action-btn">x</span>

<span use="<?php print  $item['module_id'] ?>" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue module-presets-action-btn right" >Use</span>





      </div>
    </li>
    <?php endforeach ; ?>
  </ul>
  <?php endif; ?>
</div>
<?php if(($fffound ) == false): ?>
<div class="module-presets-add-new-holder">
  <input type="hidden" name="module" value="<?php print $module_name ?>">
  <input  type="text" name="name" value="" class="mw-ui-field" xonfocus="setVisible(event);" xonblur="setVisible(event);">
  <input  type="hidden" name="module_id" value="<?php print $module_id ?>">
  <input  type="button" value="Save template"  class="mw-ui-btn module-presets-action-btn"   />
</div>
  <?php endif; ?>
<?php else : ?>
error $params['module_name'] is not set or $params['module_id'] is not set
<?php endif; ?>
