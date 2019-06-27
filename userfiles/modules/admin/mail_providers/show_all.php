<?php only_admin_access(); ?>

<?php
$mail_providers = get_modules('type=mail_provider');
?>

<div class="mw-accordion">
<?php if(!empty($mail_providers)):?>
<?php foreach($mail_providers as $provider): ?>
<div class="mw-accordion-item">
	<div class="mw-ui-box-header mw-accordion-title">
		<div class="header-holder">
			<i class="mai-setting2"></i> <?php print $provider['name'] ?>
		</div>
	</div>
	<div class="mw-accordion-content mw-ui-box mw-ui-box-content">
	<module type="<?php print $provider['module'] ?>" view="admin"/>
	</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>