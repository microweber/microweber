<?php

$settings = get_option('settings', 'init_scwCookiedefault');
$json = array();
//$settings = false;
//from ini: Array ( [cookiePolicyURL] => /privacy-i-12.html [showLiveChatMessage] => [panelTogglePosition] => right [unsetDefault] => blocked [Google_Analytics] => Array ( [enabled] => 1 [label] => Google Analytics [code] => UA-108423435-1 ) [Tawk.to] => Array ( [enabled] => [label] => Tawk.to - Live chat [code] => ) [Smartsupp] => Array ( [enabled] => [label] => Smartsupp - Live chat [code] => ) [Hotjar] => Array ( [enabled] => [label] => Hotjar - Website heatmaps [code] => ) )

if ($settings == false) {
    if (isset($params['settings'])) {
        $settings = $params['settings'];
        $json = json_decode($settings, true);
    } else {
        $json = array();
        $json['backgroundColor'] = '';
        $json['cookiePolicyURL'] = 'privacy-policy';
        $json['showLiveChatMessage'] = 'false';
        $json['panelTogglePosition'] = 'right';
        $json['unsetDefault'] = 'blocked';
        $json['Google_Analytics'] = array(
            'enabled' => 'false',
            'label' => 'Google Analytics',
            'code' => 'UA-xxxxxxxxx-1'
        );
        $json['Tawk.to'] = array(
            'enabled' => 'false',
            'label' => 'Tawk.to - Live chat',
            'code' => '12345a6b789cdef01g234567'
        );
        $json['Smartsupp'] = array(
            'enabled' => 'false',
            'label' => 'Smartsupp - Live chat',
            'code' => 'ab12c34defghi5j6k789l0m1234n567890o12345'
        );
        $json['Hotjar'] = array(
            'enabled' => 'false',
            'label' => 'Hotjar - Website heatmaps',
            'code' => '123456'
        );
    }
} else {
    $json = json_decode($settings, true);
}


if (!isset($json['cookies_policy']) OR $json['cookies_policy'] != 'y') {
    return;
}
require_once('scwCookie/scwCookie.class.php');

$scwCookie = new ScwCookie\ScwCookie($json, $params['id']);

$scwCookie->output();

if (is_admin()) print notif("Click here to edit scwCookie");
?>