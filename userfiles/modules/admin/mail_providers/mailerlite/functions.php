<?php

function get_mailerlite_api_fields()
{
	$settings = get_mail_provider_settings('mailerlite');

	$field = array();
	$field['name'] = 'api_key';
	$field['title'] = 'API Key';

	$field['value'] = '';
	if (isset($settings['api_key'])) {
		$field['value'] = $settings['api_key'];
	}

	$fields[] = $field;

	return $fields;
}
