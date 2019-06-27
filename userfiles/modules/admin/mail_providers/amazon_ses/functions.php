<?php

function get_amazon_ses_api_fields()
{
	$settings = get_mail_provider_settings('amazon_ses');
	
	$field = array();
	$field['name'] = 'api_key';
	$field['title'] = 'Api Key';
	
	if (isset($settings['api_key'])) {
		$field['value'] = $settings['api_key'];
	}
 
	$fields[] = $field;

	$field = array();
	$field['name'] = 'secret';
	$field['title'] = 'Secret';
	
	if (isset($settings['secret'])) {
		$field['value'] = $settings['secret'];
	}

	$fields[] = $field;

	$field = array();
	$field['name'] = 'region';
	$field['title'] = 'Region';
	
	if (isset($settings['region'])) {
		$field['value'] = $settings['region'];
	}

	$fields[] = $field;

	return $fields;
}