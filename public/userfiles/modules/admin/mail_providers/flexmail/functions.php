<?php

function get_flexmail_api_fields()
{
	$settings = get_mail_provider_settings('flexmail');

	////////////////////////
	$field = array();
	$field['name'] = 'api_user_id';
	$field['title'] = 'API User ID';
	$field['value'] = '';
	if (isset($settings['api_user_id'])) {
		$field['value'] = $settings['api_user_id'];
	}
	$fields[] = $field;

	////////////////////
	$field = array();
	$field['name'] = 'api_user_token';
	$field['title'] = 'API User Token';
	$field['value'] = '';
	if (isset($settings['api_user_token'])) {
		$field['value'] = $settings['api_user_token'];
	}
    $fields[] = $field;

	///////////////////
    $field = array();
    $field['name'] = 'mailing_list_id';
    $field['title'] = 'Mailing List Id';
    $field['value'] = '10000';
    if (isset($settings['mailing_list_id']) && !empty($settings['mailing_list_id'])) {
        $field['value'] = $settings['mailing_list_id'];
    }
	$fields[] = $field;

	return $fields;
}

function test_mail_flexmail() {
	
	$settings = get_mail_provider_settings('flexmail');
	
	if (!empty($settings)) {
		
		try {
			$config = new \Finlet\flexmail\Config\Config();
			$config->set('wsdl', 'http://soap.flexmail.eu/3.0.0/flexmail.wsdl');
			$config->set('service', 'http://soap.flexmail.eu/3.0.0/flexmail.php');
			$config->set('user_id', $settings['api_user_id']);
			$config->set('user_token', $settings['api_user_token']);
			$config->set('debug_mode', true);
			
			$flexmail = new \Finlet\flexmail\FlexmailAPI\FlexmailAPI($config);
			
			$categories = $flexmail->service('Category')->getAll();
			
			return 1;
			
		} catch (\Exception $e) {
			return false;
		}
		
	}
}