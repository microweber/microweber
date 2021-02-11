

<?php foreach (mw()->module_manager->get_modules('ui=0&installed=1') as $module): ?>

<iframe style="width:100%;height: 600px;" src="<?php echo site_url();?>admin/view:<?php echo $module['module'];?>">

</iframe>

<?php endforeach; ?>
