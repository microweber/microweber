<?php

event_bind('mw.mail_subscribe', function ($params) {
    sync_mail_subscriber($params);
});

api_expose('save_mail_provider');
function save_mail_provider()
{
	must_have_access();

	if (!isset($_POST['mail_provider_name'])) {
	    return false;
    }

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

	if(isset($providerSettings['active'])){

        $option = array();
        $option['option_value'] = $providerSettings['active'];
        $option['option_key'] = 'active';
        $option['option_group'] = 'mailerlite_provider';
        save_option($option);
    }


	if (isset($check['id'])) {
		$save['id'] = $check['id'];
	}

	return db_save('mail_providers', $save);
}

api_expose_admin('save_contact_form_fields', function() {

    $map = [];

    if (!empty($_POST['contact_form_map_fields'])) {
        foreach ($_POST['contact_form_map_fields'] as $field) {
           $map[$field['source']] = $field['target'];
        }
    }

    if (empty($map)) {
        return 0;
    }

   return save_option('contact_form_map_fields', json_encode($map), 'contact_form');

});

api_expose_admin('test_mail_provider');
function test_mail_provider()
{

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

	$params = array();
	$params['provider_name'] = $providerName;
	$params['single'] = true;

	return db_get('mail_providers', $params);
}

function get_mail_provider_settings($providerName)
{

	$mailProvider = get_mail_provider($providerName);

	if(is_array($mailProvider ) and isset($mailProvider['provider_settings'])){
        return json_decode($mailProvider['provider_settings'], TRUE);

    }

}


function save_mail_subscriber($mailAddress, $subscribeSource, $subscribeSourceId, $providerName) {


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

api_expose_admin('sync_mail_subscriber');
function sync_mail_subscriber($params) {

    $email = false;
    $name = false;

    foreach ($params as $key=>$value) {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $email = $value;
            $exp = explode('@', $email);
            $name = $exp[0];
        }
    }

    if ($email) {

        $provider = new \MicroweberPackages\Utils\Mail\MailSubscriber();

        if (isset($params['list_id'])) {
          //  $getFormLists = get_form_lists("?id=".$params['list_id'].'&limit=1');
            $getFormLists = get_form_lists(['id' => $params['list_id'],'limit'=>1]);
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

        $mapFields = get_option('contact_form_map_fields','contact_form');
        $mapFields = json_decode($mapFields, true);
        if (!empty($mapFields)) {
            foreach ($params as $key=>$value) {
                if (isset($mapFields[$key])) {
                    if ($mapFields[$key] == 'email') {
                        $provider->setEmail($value);
                    }
                    if ($mapFields[$key] == 'name') {
                        $provider->setFirstName($value);
                    }
                    if ($mapFields[$key] == 'last_name') {
                        $provider->setLastName($value);
                    }
                    if ($mapFields[$key] == 'phone') {
                        $provider->setPhone($value);
                    }
                    if ($mapFields[$key] == 'city') {
                        $provider->setCity($value);
                    }
                    if ($mapFields[$key] == 'country') {
                        $provider->setCountryRegistration($value);
                    }
                    if ($mapFields[$key] == 'company_name') {
                        $provider->setCompanyName($value);
                    }
                    if ($mapFields[$key] == 'company_position') {
                        $provider->setCompanyPosition($value);
                    }
                    if ($mapFields[$key] == 'state') {
                        $provider->setState($value);
                    }
                    if ($mapFields[$key] == 'zip') {
                        $provider->setZip($value);
                    }
                    if ($mapFields[$key] == 'message') {
                        $provider->setMessage($value);
                    }
                }
            }
        }


    /*    $ignoreFields = array('name','email','message','rel_id','rel_type','for','for_id','captcha','module_name','list_id','option_group');

        foreach($params as $key=>$value) {
            if (in_array(mb_strtolower($key), $ignoreFields)) {
                continue;
            }

            $provider->addCustomField(array(
                'key'=>$key,
                'value'=>$value
            ));
        }*/

       return $provider->subscribe(true);
    }
}
