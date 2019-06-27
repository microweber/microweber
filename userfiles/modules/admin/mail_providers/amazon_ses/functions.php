<?php

function get_amazon_ses_api_fields()
{
	$field = array();
	$field['name'] = 'api_key';
	$field['title'] = 'Api Key';

	$fields[] = $field;

	$field = array();
	$field['name'] = 'secret';
	$field['title'] = 'Secret';

	$fields[] = $field;

	$field = array();
	$field['name'] = 'region';
	$field['title'] = 'Region';

	$fields[] = $field;

	return $fields;
}