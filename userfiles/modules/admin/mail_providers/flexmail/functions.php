<?php

function get_flexmail_api_fields()
{
	$settings = get_mail_provider_settings('flexmail');

	$field = array();
	$field['name'] = 'api_user_id';
	$field['title'] = 'API User ID';

	$field['value'] = '';
	if (isset($settings['api_user_id'])) {
		$field['value'] = $settings['api_user_id'];
	}

	$fields[] = $field;

	$field = array();
	$field['name'] = 'api_user_token';
	$field['title'] = 'API User Token';

	$field['value'] = '';
	if (isset($settings['api_user_token'])) {
		$field['value'] = $settings['api_user_token'];
	}

	$fields[] = $field;

	return $fields;
}