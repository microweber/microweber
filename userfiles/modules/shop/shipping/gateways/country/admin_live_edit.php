<?php only_admin_access(); ?>

<div class="mw_simple_tabs mw_tabs_layout_simple">
	<ul class="mw_simple_tabs_nav">
		<li><a href="javascript:;" class="actSive">
			<?php _e("Settings"); ?>
			</a></li>
		<li><a href="javascript:;">
			<?php _e("Skin/Template"); ?>
			</a></li>
	</ul>
	<div class="tab">
	<?php 
	
 
	include_once($config['path_to_module'].'admin_backend.php'); ?>

	</div>
	<div class="tab">
	
	 
	
	<module type="admin/modules/templates" id="shipping_list_templ"  for-module="<?php print($params['data-type']); ?>"  />
</div>
	<div class="mw_clear"></div>
	<div class="vSpace"></div>
</div>
