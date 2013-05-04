<?php
if (!defined("MODULE_DB_IP2COUNTRY")) {
	define('MODULE_DB_IP2COUNTRY', MW_TABLE_PREFIX . 'ip2country');
}

function ip2country($ip = false, $key = 'country_name') {
	if ($ip == false) {
		$ip = USER_IP;
	}
	/*
	 * ex
	 * ["id"]=> string(1) "4"
	 * ["ip"]=> string(11) "77.70.8.202"
	 * ["ip_long"]=> NULL
	 * ["country_code"]=> string(2) "BG"
	 * ["country_name"]=> string(8) "Bulgaria"
	 * ["region"]=> string(11) "Grad Sofiya"
	 * ["city"]=> string(5) "Sofia"
	 * ["latitude"]=> string(7) "42.6833"
	 * ["longitude"]=> string(7) "23.3167"
	 *
	 * */

	$table = MODULE_DB_IP2COUNTRY;
	$params = array();
	$params['table'] = $table;
	$params['ip'] = $ip;
	$params['limit'] = 1;
	$get = get($params);
	if ($get == false) {
		$remote_host = 'http://api.microweber.net';
		$service = "/service/ip2country/?ip=" . $ip;
		$remote_host_s = $remote_host . $service;
		//d($remote_host_s);
		$get_remote = false;
		 $get_remote = @url_download($remote_host_s);

		if ($get_remote != false) {
			$get_remote = json_decode($get_remote, 1);
			if ($get_remote != false) {

				$params = $get = $get_remote;
				$params['ip'] = $ip;

				if(isset($params['country_name']) and $params['country_name'] == '' ){
					$params['country_name'] = "Unknown";

				} else if(!isset($params['country_name'])) {
				 $params['country_name'] = "Unknown";
				}
				//d($params);
				$s = save_data($table, $params);
				$get = $params;
			}
		}


	} else {
		$get = $get[0];
	}
	if (isset($get[$key])) {
		return $get[$key];
	}
	//d($get);
	//return $get;

}
