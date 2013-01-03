<?php

function api_expose($function_name) {
	static $index = ' ';
	if (is_bool($function_name)) {

		return $index;
	} else {
		$index .= ' ' . $function_name;
	}
}

function api_hook($function_name, $next_function_name = false) {
	static $index = array();

	if (is_bool($function_name)) {
		$index = array_unique($index);
		return $index;
	} else {

		$index[$function_name][] = $next_function_name;

		//  $index .= ' ' . $function_name;
	}
}

function document_ready($function_name) {
	static $index = ' ';
	if (is_bool($function_name)) {

		return $index;
	} else {
		$index .= ' ' . $function_name;
	}
}

function execute_document_ready($l) {

	$document_ready_exposed = (document_ready(true));

	if ($document_ready_exposed != false) {
		$document_ready_exposed = explode(' ', $document_ready_exposed);
		$document_ready_exposed = array_unique($document_ready_exposed);
		$document_ready_exposed = array_trim($document_ready_exposed);

		foreach ($document_ready_exposed as $api_function) {
			if (function_exists($api_function)) {
				//                for ($index = 0; $index < 20000; $index++) {
				//                     $l = $api_function($l);
				//                }
				$l = $api_function($l);
			}
		}
	}
	$l = parse_micrwober_tags($l, $options = false);

	return $l;
}

/* JS Usage:
 *
 * var source = new EventSource('<? print site_url('api/event_stream')?>');
 *	source.onmessage = function (event) {
 *
 * 	mw.$('#mw-admin-manage-orders').html(event.data);
 *	};
 *
 *
 *  */
api_expose('event_stream');
function event_stream() {

	header("Content-Type: text/event-stream\n\n");

	for ($i = 0; $i < 10; $i++) {
	 
		echo 'data: '.$i . rand() . rand() . rand() . rand() . rand() . rand() . "\n";
		 
	}

	 exit();
}
