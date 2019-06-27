<?php
api_expose('save_mail_provider');
function save_mail_provider()
{
	only_admin_access();

	$providerName = $_POST['mail_provider_name'];
	unset($_POST['mail_provider_name']);

	$providerSettings = array();
	foreach ($_POST as $key => $value) {
		if (is_string($key) && is_string($value)) {
			$providerSettings[$key] = $value;
		}
	}

	$check = db_get(array(
		'table' => 'forms_mail_providers_settings',
		'provider_name' => $providerName,
		'single' => true
	));

	$save = array(
		'provider_name' => $providerName,
		'provider_settings' => json_encode($providerSettings),
		'is_active' => 1
	);

	if (isset($check['id'])) {
		$save['id'] = $check['id'];
	}

	return db_save('forms_mail_providers_settings', $save);
}

function get_mail_provider($providerName)
{
	only_admin_access();
	
	$params = array();
	$params['provider_name'] = $providerName;
	$params['single'] = true;
	
	return db_get('forms_mail_providers_settings', $params);
}