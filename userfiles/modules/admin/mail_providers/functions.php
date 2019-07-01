<?php

event_bind('mw.mail_subscribe', function ($params) {
	
	if (isset($params['email'])) {
	
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
		
		if (isset($params['first_name'])) {
			$provider->setFirstName($params['first_name']);
		}
		
		$provider->setEmail($params['email']);
		
		if (isset($params['first_name'])) {
			$provider->setFirstName($params['first_name']);
		}
		
		if (isset($params['name'])) {
			$provider->setFirstName($params['name']);
		}
		
		if (isset($params['last_name'])) {
			$provider->setLastName($params['last_name']);
		}
		
		if (isset($params['phone'])) {
			$provider->setPhone($params['phone']);
		}
		
		if (isset($params['company_name'])) {
			$provider->setCompanyName($params['company_name']);
		}
		
		if (isset($params['company_position'])) {
			$provider->setCompanyPosition($params['company_position']);
		}
		
		if (isset($params['country_registration'])) {
			$provider->setCountryRegistration($params['country_registration']);
		}
		
		if (isset($params['message'])) {
			$provider->setMessage($params['message']);
		}
		
		if (isset($params['option_group'])) {
			$provider->setSubscribeFrom($params['option_group']);
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
	return json_decode($mailProvider['provider_settings'], TRUE);
}


function save_mail_subscriber($subscribeSource, $subscribeSourceId, $providerName) {
	
	only_admin_access();
	
	$provider = get_mail_provider($providerName);
	
	if (isset($provider['id'])) {
		
		$params = array();
		$params['rel_type'] = $subscribeSource;
		$params['rel_id'] = $subscribeSourceId;
		$params['mail_provider_id'] = $provider['id'];
		
		return db_save('mail_subscribers', $params);
		
	}
}

function get_mail_subscriber($subscribeSource, $subscribeSourceId, $providerName) {
	
	only_admin_access();
	
	$provider = get_mail_provider($providerName);
	
	if (isset($provider['id'])) {
		
		$params = array();
		$params['rel_type'] = $subscribeSource;
		$params['rel_id'] = $subscribeSourceId;
		$params['mail_provider_id'] = $provider['id'];
		$params['single'] = true;
		$params['no_cache'] = true;
		
		return db_get('mail_subscribers', $params);
	
	}
}