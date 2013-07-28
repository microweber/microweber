<?php



















function get_domain_from_str($address) {
	//$address = 'clients1.sub3.google.co.uk';
	$address = gethostbyaddr($address);
	$parsed_url = parse_url($address);
	if (!isset($parsed_url['host'])) {
		if (isset($parsed_url['path'])) {
			$parsed_url['host'] = $parsed_url['path'];
		}
	}
	$check = esip($parsed_url['host']);
	$host = $parsed_url['host'];
	if ($check == FALSE) {
		if ($host != "") {
			$host = __domain_name($host);
		} else {
			$host = __domain_name($address);
		}
	} else {

		//$host = __domain_name($address);

	}
	return $host;
}
