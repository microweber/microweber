<?php

use MicroweberPackages\Country\Models\Country;

$user = get_user();

if (empty($user)) {
    return;
}


$findCustomer = \MicroweberPackages\Customer\Models\Customer::where('user_id', Auth::id())->first();

$name = '';
$first_name = '';
$last_name = '';
$email = '';
$phone = '';

if ($findCustomer) {
    $name = $findCustomer['name'];
    $first_name = $findCustomer['first_name'];
    $last_name = $findCustomer['last_name'];
    $email = $findCustomer['email'];
    $phone = $findCustomer['phone'];
}

$billing_address = [
    'name' => '',
    'company_name' => '',
    'company_id' => '',
    'company_vat' => '',
    'company_vat_registered' => '',
    'address_street_1' => '',
    'address_street_2' => '',
    'city' => '',
    'zip' => '',
    'state' => '',
    'country_id' => '',
];
$shipping_address = [
    'name' => '',
    'company_name' => '',
    'company_id' => '',
    'company_vat' => '',
    'company_vat_registered' => '',
    'address_street_1' => '',
    'address_street_2' => '',
    'city' => '',
    'zip' => '',
    'state' => '',
    'country_id' => '',
];

if ($findCustomer) {
    $findAddressBilling = \MicroweberPackages\Customer\Models\Address::where('type', 'billing')->where('customer_id', $findCustomer->id)->first();
    if ($findAddressBilling) {
        foreach ($findAddressBilling->toArray() as $addressKey => $addressValue) {
            $billing_address[$addressKey] = $addressValue;
        }
    }
    $findAddressShipping = \MicroweberPackages\Customer\Models\Address::where('type', 'shipping')->where('customer_id', $findCustomer->id)->first();
    if ($findAddressShipping) {
        foreach ($findAddressShipping->toArray() as $addressKey => $addressValue) {
            $shipping_address[$addressKey] = $addressValue;
        }
    }
}

$dataCountry = countries_list();

$countries = [];
$getCountries = \MicroweberPackages\Country\Models\Country::all();
if ($getCountries->count() > 0) {
    $countries = $getCountries->toArray();
}

$disabledCountriesForShipping = [];


if ($countries) {
    $shippingModule = app()->shipping_manager->getDefaultDriver();
    if (method_exists($shippingModule, 'getCountries')) {
        $countriesFromShippingDriver = $shippingModule->getCountries();
        if ($countriesFromShippingDriver) {
            foreach ($countries as $countries_key => $countries_val) {
                $found = false;
                foreach ($countriesFromShippingDriver as $enabledCountry) {
                    if ($countries_val["name"] == $enabledCountry) {
                        $found = true;
                    }
                }
                if(!$found){
                    unset($countries[$countries_key]);
                }

            }
        }

     }
}


// Template settings
$module_template = get_module_option('template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (isset($template_file) and is_file($template_file) != false) {
    include($template_file);
} else {
    $template_file = module_templates($config['module'], 'default');
    include($template_file);
}
