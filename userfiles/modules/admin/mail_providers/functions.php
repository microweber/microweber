<?php

event_bind('mw.mail_subscribe', function ($params) {

	$email = false;
	$name = false;
	$message = false;
	$phone = false;
	$lastName = false;
	
	if (isset($params['email'])) {
		$email = $params['email'];
	}
	
	if (isset($params['name'])) {
		$name = $params['name'];
	}
	
	if (isset($params['first_name'])) {
		$name = $params['first_name'];
	}
	
	if (isset($params['last_name'])) {
		$lastName = $params['last_name'];
	}
	
	if (isset($params['phone'])) {
		$phone = $params['phone'];
	}
	
	if (isset($params['message'])) {
		$message = $params['message'];
	}
	
	foreach($params as $kParam=>$kValue) {
		$kParamLower = mb_strtolower($kParam);
		if ($kParamLower == 'email') {
			$email = $kValue;
		}
		if ($kParamLower == 'message') {
			$message = $kValue;
		}
		if ($kParamLower == 'phone') {
			$phone = $kValue;
		}
		if ($kParamLower == 'name' || $kParamLower == 'firstname' || $kParamLower == 'first_name' || $kParamLower == 'firstName') {
			$name = $kValue;
		}
		if ($kParamLower == 'last_name' || $kParamLower == 'lastname' || $kParamLower == 'lastName') {
			$lastName = $kValue;
		}
	}

	if ($email) {

		$provider = new \Microweber\Utils\MailSubscriber();
		
		if (isset($params['list_id'])) {
			$getFormLists = get_form_lists("?id=".$params['list_id'].'&limit=1');
			if (isset($getFormLists[0]['title'])) {
				$provider->setListTitle($getFormLists[0]['title']);
			}
		}
		
		if (isset($params['rel_id'])) {
			$provider->setSubscribeSourceId($params['rel_id']);
		}
		
		if (isset($params['rel_type'])) {
			$provider->setSubscribeSource($params['rel_type']);
		}
		
		$provider->setEmail($email);
		$provider->setFirstName($name);
		$provider->setLastName($lastName);
		$provider->setPhone($phone);
		
		if (isset($params['company_name'])) {
			$provider->setCompanyName($params['company_name']);
		}
		
		if (isset($params['company_position'])) {
			$provider->setCompanyPosition($params['company_position']);
		}
		
		if (isset($params['country_registration'])) {
			$provider->setCountryRegistration($params['country_registration']);
		}
		
		$provider->setMessage($message);
		
		if (isset($params['option_group'])) {
			$provider->setSubscribeFrom($params['option_group']);
		}
		
		$ignoreFields = array('name','email','message','rel_id','rel_type','for','for_id','captcha','module_name','list_id','option_group');
		
		foreach($params as $key=>$value) {
			if (in_array(mb_strtolower($key), $ignoreFields)) {
				continue;
			}
			
			$provider->addCustomField(array(
				'key'=>$key,
				'value'=>$value
			));
		}

		$provider->subscribe();
	}
	
});

api_expose('save_mail_provider');
function save_mail_provider()
{
	only_admin_access();

	$providerName = $_POST['mail_provider_name'];
	unset($_POST['mail_provider_name']);

	$providerSettings = array();
	foreach ($_POST as $key => $value) {
		if (is_string($key) && is_string($value)) {
			$providerSettings[$key] = $value;
		}
	}

	$check = db_get(array(
		'table' => 'mail_providers',
		'provider_name' => $providerName,
		'single' => true
	));

	$save = array(
		'provider_name' => $providerName,
		'provider_settings' => json_encode($providerSettings),
		'is_active' => 1
	);

	if (isset($check['id'])) {
		$save['id'] = $check['id'];
	}

	return db_save('mail_providers', $save);
}

api_expose('test_mail_provider');
function test_mail_provider()
{
	only_admin_access();
	
	if (isset($_POST['mail_provider_name'])) {
		
		$mailProviderName = 'test_mail_' . $_POST['mail_provider_name'];
		
		if (function_exists($mailProviderName)) {
			return $mailProviderName();
		}
		
		return false;
	}
}

function get_mail_provider($providerName)
{
	only_admin_access();

	$params = array();
	$params['provider_name'] = $providerName;
	$params['single'] = true;

	return db_get('mail_providers', $params);
}

function get_mail_provider_settings($providerName)
{
	only_admin_access();
	
	$mailProvider = get_mail_provider($providerName);

	if(is_array($mailProvider ) and isset($mailProvider['provider_settings'])){
        return json_decode($mailProvider['provider_settings'], TRUE);

    }

}


function save_mail_subscriber($mailAddress, $subscribeSource, $subscribeSourceId, $providerName) {
	
	only_admin_access();
	
	$provider = get_mail_provider($providerName);
	
	if (isset($provider['id'])) {
		
		$params = array();
		$params['mail_address'] = $mailAddress;
		$params['rel_type'] = $subscribeSource;
		$params['rel_id'] = $subscribeSourceId;
		$params['mail_provider_id'] = $provider['id'];
		
		return db_save('mail_subscribers', $params);
		
	}
}

function get_mail_subscriber($mailAddress, $subscribeSource, $subscribeSourceId, $providerName) {
	
	only_admin_access();
	
	$provider = get_mail_provider($providerName);
	
	if (isset($provider['id'])) {
		
		$params = array();
		$params['mail_address'] = $mailAddress;
		$params['rel_type'] = $subscribeSource;
		$params['rel_id'] = $subscribeSourceId;
		$params['mail_provider_id'] = $provider['id'];
		$params['single'] = true;
		$params['no_cache'] = true;
		
		return db_get('mail_subscribers', $params);
	
	}
}