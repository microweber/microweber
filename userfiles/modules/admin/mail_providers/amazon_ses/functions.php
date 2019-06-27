<?php

function get_amazon_ses_api_fields()
{
	
	$mailProvider = get_mail_provider('amazon_ses');
	$providerSettings = json_decode($mailProvider['provider_settings'], TRUE);
	
	$field = array();
	$field['name'] = 'api_key';
	$field['title'] = 'Api Key';
	
	if (isset($providerSettings['api_key'])) {
		$field['value'] = $providerSettings['api_key'];
	}
 
	$fields[] = $field;

	$field = array();
	$field['name'] = 'secret';
	$field['title'] = 'Secret';
	
	if (isset($providerSettings['secret'])) {
		$field['value'] = $providerSettings['secret'];
	}

	$fields[] = $field;

	$field = array();
	$field['name'] = 'region';
	$field['title'] = 'Region';
	
	if (isset($providerSettings['region'])) {
		$field['value'] = $providerSettings['region'];
	}

	$fields[] = $field;

	return $fields;
}