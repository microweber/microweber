<script>
$(document).ready(function() {
	var mail_provider_settings_form_class = '.mail-provider-flexmail-settings-form';
	$(mail_provider_settings_form_class).on('change paste', 'input, select, textarea', function(){
		$.post(mw.settings.api_url + 'save_mail_provider', $(mail_provider_settings_form_class).serialize(), function(data) {
			mw.notification.success('Settings are saved.'); 
		});
	});

	$('.mail-provider-test-api-flexmail').click(function() {
		
		$.post(mw.settings.api_url + 'save_mail_provider', $(mail_provider_settings_form_class).serialize());
		
		mw.notification.warning('Testing...');
		$.post(mw.settings.api_url + 'test_mail_provider', $(mail_provider_settings_form_class).serialize(), function(data) {
			if (data === '1') {
				mw.notification.success('Sucessfull connecting.');
			} else {	
				mw.notification.error('Wrong mail provider settings.');
			}
		});
		
	});
});
</script>

<form class="mail-provider-flexmail-settings-form" method="post">
<input type="hidden" name="mail_provider_name" value="flexmail" />
<?php foreach (get_flexmail_api_fields() as $field): ?>
<div class="demobox">
	<label class="mw-ui-label"><?php echo $field['title']; ?></label> 
	<input type="text" value="<?php echo $field['value']; ?>" name="<?php echo $field['name']; ?>" class="mw-ui-field w100 mw_option_field">
</div>
<br />
<?php endforeach; ?>
<div class="demobox">
<button type="button" class="mw-ui-btn mw-ui-btn-info mail-provider-test-api-flexmail">Test Api</button>
</div>
</form>