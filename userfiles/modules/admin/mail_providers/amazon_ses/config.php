<?php
$config = array();
$config['name'] = "AmazonSes";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['position'] = 100;
$config['type'] = "mail_provider";


$check = db_get(array(
	'table' => 'forms_mail_providers_settings',
	'provider_name' => $config['name'],
	'single' => true
));
if (empty($check)) {
	db_save('forms_mail_providers_settings', array(
		'provider_name' => $config['name'],
		'provider_settings' => json_encode(get_amazon_ses_api_fields()),
		'is_active' => 1
	));
}