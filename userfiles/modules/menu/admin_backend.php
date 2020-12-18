<?php  //$rand = $params['id']; ?>
<div class="mw-module-admin-wrap">
 <?php if(isset($params['backend'])): ?>
<module type="admin/modules/info" />
<?php endif; ?>
    <div class="menus-index-bar">
       <?php 
	 include($config['path_to_module'].'admin_live_edit_tab1.php');   ?>
    </div>
</div>



 