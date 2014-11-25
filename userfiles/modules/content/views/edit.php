<?php
 
 $wrapper_class = 'mw-edit-content-item-admin'; 
 
  if(isset($params['live_edit'])){
	$wrapper_class = 'module-live-edit-settings'; 
	 
 }?>

<div class="<?php print $wrapper_class; ?>">



<?php event_trigger('content.edit.main',$params); ?>

  <?php $content_edit_modules = mw()->ui->module('content.edit.main'); ?>
  <?php $modules = array(); ?>
  <?php 
 
if (!empty($content_edit_modules) and !empty($data)) {
    foreach ($content_edit_modules as $k1=>$content_edit_module) {
		if(isset($content_edit_module['module'])){
			if(isset($content_edit_module['content_type']) and isset($content_edit_module['subtype'])){
				if(isset($data['content_type']) and isset($data['subtype'])){
					if(($data['content_type']) == ($content_edit_module['content_type'])){
						if(($data['subtype']) == ($content_edit_module['subtype'])){
							 $modules[] = $content_edit_module['module'];
						}
					}
				}
			}
		}
     }
	$modules = array_unique($modules);
}

?>
  <?php if(!empty($modules)): ?>
  <?php foreach($modules as $module) : ?>
  <?php print load_module($module,$data); ?>
  <?php endforeach; ?>
  <?php else:  ?>
  <?php include __DIR__ . DS . 'edit_default.php';  ?>
  <?php endif; ?>
</div>
