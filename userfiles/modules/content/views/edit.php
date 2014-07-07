<?php $content_edit_modules = mw()->module->ui('content.edit.main'); ?>
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
		
		
		/*foreach ($data as $k=>$v) {
			if(isset($content_edit_module[$k])){
				$v1 = $content_edit_module[$k];
				$v2 = $v;
				if(trim($v1) == trim($v2)){
					if(isset($content_edit_module['module'])){
						 $modules[] = $content_edit_module['module'];
					}
				}
			}
			
		}*/
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