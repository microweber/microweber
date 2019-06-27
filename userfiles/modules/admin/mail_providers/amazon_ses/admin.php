<script>
$(document).ready(function() {
	var mail_provider_settings_form_class = '.mail-provider-amazon-ses-settings-form';
	$(mail_provider_settings_form_class).on('change paste', 'input, select, textarea', function(){
		$.post(mw.settings.api_url + 'save_mail_provider', $(mail_provider_settings_form_class).serialize());
	});
});
</script>

<form class="mail-provider-amazon-ses-settings-form" method="post">
<input type="hidden" name="mail_provider_name" value="amazon_ses" />
<?php foreach (get_amazon_ses_api_fields() as $field): ?>
<div class="demobox">
	<label class="mw-ui-label"><?php echo $field['title']; ?></label> 
	<input type="text" value="<?php echo $field['value']; ?>" name="<?php echo $field['name']; ?>" class="mw-ui-field w100 mw_option_field">
</div>
<br />
<?php endforeach; ?>
</form>