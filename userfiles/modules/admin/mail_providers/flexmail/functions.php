<?php

function get_flexmail_api_fields()
{
	$field = array();
	$field['name'] = 'api_user_id';
	$field['title'] = 'API User ID';

	$fields[] = $field;

	$field = array();
	$field['name'] = 'api_user_token';
	$field['title'] = 'API User Token';

	$fields[] = $field;

	return $fields;
}