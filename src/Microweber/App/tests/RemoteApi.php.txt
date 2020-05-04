<?php 

$domain = "localhost";
$api_key = "demo";
$resp = mw_test_remote_api_curl($domain, $api_key);
var_dump($resp);

function mw_test_remote_api_curl($domain, $api_key)
{


    $postfields = array();
    $postfields["api_key"] = $api_key;
    $postfields["content_type"] = "page";

    $ch = curl_init();

    $cookie = tempnam("/tmp", "CURLCOOKIE" . md5(serialize($postfields)));

    $url = 'http://' . $domain . '/api/get_content_admin';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    $data = json_decode($data, true);
    curl_close($ch);
    return $data;

}