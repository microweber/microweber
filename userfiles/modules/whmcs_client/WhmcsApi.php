<?php

namespace Microweber\whmcs_client;



class WhmcsApi
{
	
	
	
	function check_status(){
		
		
	}
	
	
	
	function remote_exec($params)
{


    if (!is_array($params)) {
        $params = parse_params($params);
    }

     
    $whm_config = array();
    if (!isset($whm_config['url'])) {
        return array('error' => 'URL is not set');
    }
    if (!isset($whm_config['username'])) {
        return array('error' => 'username is not set');
    }
    if (!isset($whm_config['password'])) {
        return array('error' => 'password is not set');
    }


    $url = $whm_config['url'];
    $username = $whm_config['username']; # Admin username goes here
    $password = $whm_config['password']; # Admin password goes here
    $postfields = $params;
    $postfields["username"] = $username;
    $postfields["password"] = md5($password);
    $postfields["responsetype"] = "json";


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    $results = array();
    $data = explode(";", $data);
    foreach ($data as $temp) {
        $temp = explode("=", $temp);
        if (isset($temp[0]) and isset($temp[1])) {
            $results[$temp[0]] = $temp[1];
        }
    }
    // var_dump($results);
    if (isset($results["result"]) and $results["result"] == "success") {
        # Result was OK!

        return $results;

    } else if (isset($results["message"])) {

        return array('error' => $results["message"]);

    } else {
        return $results;
    }


}
	 
}