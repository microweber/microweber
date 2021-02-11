<?php only_admin_access() ?>
<?php foreach (mw()->module_manager->get_modules('ui=any&installed=1') as $module): ?>

<h1><?php echo $module['module'];?></h1>
<iframe style="width:100%;height: 600px;" src="<?php echo site_url();?>admin/view:<?php echo $module['module'];?>"></iframe>

<?php endforeach; ?>
