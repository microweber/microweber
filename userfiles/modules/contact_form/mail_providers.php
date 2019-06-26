<?php

function get_mail_providers()
{
	$providers = array();
	
	/* $provider = array();
	$provider['title'] = 'Mailchimp';
	$provider['name'] = 'mailchimp';
	$provider['fields'] = get_mailchimp_api_fields();
	$providers[] = $provider;
	
	$provider = array();
	$provider['title'] = 'Mailgun';
	$provider['name'] = 'mailgun';
	$provider['fields'] = get_mailgun_api_fields();
	$providers[] = $provider;
	
	$provider = array();
	$provider['title'] = 'Mandril';
	$provider['name'] = 'mandril';
	$provider['fields'] = get_mandril_api_fields();
	$providers[] = $provider; */
	
	$provider = array();
	$provider['title'] = 'MailerLite';
	$provider['name'] = 'mailerlite';
	$provider['fields'] = get_mailerlite_api_fields();
	$providers[] = $provider; 
	
	$provider = array();
	$provider['title'] = 'Flexmail';
	$provider['name'] = 'flexmail';
	$provider['fields'] = get_flexmail_api_fields();
	$providers[] = $provider;
	
	/* 
	$provider = array();
	$provider['title'] = 'Sparkpost';
	$provider['name'] = 'sparkpost';
	$provider['fields'] = get_sparkpost_api_fields();
	$providers[] = $provider;
	
	$provider = array();
	$provider['title'] = 'Amazon SES';
	$provider['name'] = 'amazon_ses';
	$provider['fields'] = get_amazon_ses_api_fields(); 
	$providers[] = $provider;*/
	
	return $providers;
}

function get_mailchimp_api_fields()
{
	$field = array();
	$field['name'] = 'secret';
	$field['title'] = 'Secret';

	$fields[] = $field;

	return $fields;
}

function get_mailgun_api_fields()
{
	$field = array();
	$field['name'] = 'secret';
	$field['title'] = 'Secret';

	$fields[] = $field;

	return $fields;
}

function get_mandril_api_fields()
{
	$field = array();
	$field['name'] = 'secret';
	$field['title'] = 'Secret';

	$fields[] = $field;

	return $fields;
}

function get_mailerlite_api_fields()
{
	$field = array();
	$field['name'] = 'api_key';
	$field['title'] = 'API Key';
	
	$fields[] = $field;
	
	return $fields;
}

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

function get_sparkpost_api_fields()
{
	$field = array();
	$field['name'] = 'secret';
	$field['title'] = 'Secret';

	$fields[] = $field;

	return $fields;
}

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