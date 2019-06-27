<?php

function get_mailerlite_api_fields()
{
	
	$mailProvider = get_mail_provider('mailerlite');
	$providerSettings = json_decode($mailProvider['provider_settings'], TRUE);
	
	$field = array();
	$field['name'] = 'api_key';
	$field['title'] = 'API Key';
	
	if (isset($providerSettings['api_key'])) {
		$field['value'] = $providerSettings['api_key'];
	}
	
	$fields[] = $field;

	return $fields;
}
