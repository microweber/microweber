<?php

function get_flexmail_api_fields()
{
	$mailProvider = get_mail_provider('flexmail');
	$providerSettings = json_decode($mailProvider['provider_settings'], TRUE);
	
	$field = array();
	$field['name'] = 'api_user_id';
	$field['title'] = 'API User ID';
	
	if (isset($providerSettings['api_user_id'])) {
		$field['value'] = $providerSettings['api_user_id'];
	}

	$fields[] = $field;

	$field = array();
	$field['name'] = 'api_user_token';
	$field['title'] = 'API User Token';
	
	if (isset($providerSettings['api_user_token'])) {
		$field['value'] = $providerSettings['api_user_token'];
	}

	$fields[] = $field;

	return $fields;
}