<?php

use MailerLiteApi\MailerLite;

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

function test_mail_mailerlite() {
	
	$settings = get_mail_provider_settings('mailerlite');
	
	if (!empty($settings)) {
		try {
			$groupsApi = (new MailerLite($settings['api_key']))->groups();
			$allGroups = $groupsApi->get();
			
			foreach($allGroups as $group) {
				if (isset($group->error->code)) {
					throw new \Exception($group->error->message);
				}
			}
			
			return 1;
			
		} catch (\Exception $e) {
			return false;
		}
	}
	
}