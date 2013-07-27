<?php

only_admin_access();

 	
	 $update_api = new \Mw\Update();
 
	

 
 ?>apply_updates
 
 
 <?php  if(isset($_POST['mw_version'])){ ?>
  
 <h2><?php _e("Installing new version of Microweber"); ?>: <?php print  $_POST['mw_version'] ?></h2>
<textarea>
<?php $iudates = $update_api -> install_version($_POST['mw_version']); 
d($iudates);
?>
</textarea>
 
<?php }  ?>

 <?php
 if(isset($_POST['modules'])){ ?>
 <?php if(isarr($_POST['modules'])): ?>
  <?php foreach($_POST['modules']  as $item): ?> 
 <h2>Installing module: <?php print  $item ?></h2>
<textarea>
<?php $iudates = $update_api -> install_module($item); 
d($iudates);
?>
</textarea>
 <?php endforeach ; ?>
<?php endif; ?>
<?php }  ?>



 <?php 
 if(isset($_POST['elements'])){ ?>
 <?php if(isarr($_POST['elements'])): ?>
  <?php foreach($_POST['elements']  as $item): ?> 
 <h2>Installing layouts: <?php print  $item ?></h2>
<textarea>
<?php $iudates = $update_api -> install_element($item); 
d($iudates);
?>
</textarea>
 <?php endforeach ; ?>
<?php endif; ?>
<?php }  ?>



 <?php 
 if(isset($_POST['module_templates'])){ ?>
 <?php if(isarr($_POST['module_templates'])): ?>
  <?php foreach($_POST['module_templates']  as $k=> $item): ?> 
 <h2>Installing module template: <?php print  $item ?> (for <em><?php print $k ?></em>)</h2>
<textarea>
<?php $iudates = $update_api -> install_module_template($k,$layout_file); 
d($iudates);
?>
</textarea>
 <?php endforeach ; ?>
<?php endif; ?>
<?php }  ?>