<?php
only_admin_access();

$notification_id = (int) $params['notification_id'];

$data = mw()->notifications_manager->get('single=1&id=' . $notification_id);
$form_data = mw()->forms_manager->get_entires('single=1&id=' . $data['rel_id']);

if (empty($form_data)) {
	echo 'Form data are empty.';
	return;
}

$customFields = array();
$removeRield = array('for_id','for','module_name');
foreach($form_data['custom_fields'] as $kFiled=>$vField) {
	if (in_array($kFiled, $removeRield)) {
		continue;
	}
	$customFields[$kFiled] = $vField;
}
?>

<h3 style="color: #2f9cff;"> View contact form entry</h3>
Created at: <?php echo $form_data['created_at']; ?>
<br />
<br />
<br />
<?php if (is_array($customFields)): ?>
	<?php foreach ($customFields as $key => $value): ?>
	
	<b style="font-size:13px;text-transform:uppercase"><?php echo $key; ?></b>
	<br />
	<?php if(is_string($value)): ?>
	<?php echo $value; ?>
	<?php endif; ?>
	<?php if(is_array($value)): ?>
	<?php foreach($value as $v):?>
	<?php echo $v; ?> <br />
	<?php endforeach; ?>
	<?php endif; ?>
	<br /><br /> 
	<?php endforeach; ?>
<?php endif; ?>


<div class="pull-right" style="margin-top:10%;">
<a href="javascript:mw.notif_item_read(<?php echo $notification_id; ?>);" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-notification">
<i class="mw-icon-web-checkmark"></i> 
Mark as read
</a>
<a href="javascript:mw.notif_item_reset(<?php echo $notification_id; ?>);" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-warn">
Mark as unread
</a>
<a href="<?php print admin_url() ?>view:modules/load_module:contact_form" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-info">
<i class="mw-icon-live"></i> 
View all
</a>
</div>