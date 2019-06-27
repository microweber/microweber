<?php

function get_mailerlite_api_fields()
{
	$field = array();
	$field['name'] = 'api_key';
	$field['title'] = 'API Key';
	$field['value'] = '';
	
	$fields[] = $field;

	return $fields;
}
