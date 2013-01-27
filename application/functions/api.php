<?php
function api($function_name, $params = false) {
	static $c;

	if ($c == false) {
		if (!defined('MW_API_RAW')) {
			define('MW_API_RAW', true);
		}
		$c = new MwController();
		
	}
	$res = $c -> api($function_name, $params);
		return $res;

}

function api_expose($function_name) {
	static $index = ' ';
	if (is_bool($function_name)) {

		return $index;
	} else {
		$index .= ' ' . $function_name;
	}
}

function exec_action($api_function, $data = false) {

	$hooks = action_hook(true);

	if (isset($hooks[$api_function]) and is_array($hooks[$api_function]) and !empty($hooks[$api_function])) {

		foreach ($hooks[$api_function] as $hook_key => $hook_value) {

			if (function_exists($hook_value)) {
				if ($data != false) {
					$hook_value($data);
				} else {

					$hook_value();
				}
				unset($hooks[$api_function][$hook_key]);

			}
		}
	}
}

$mw_action_hook_index = array();
function action_hook($function_name, $next_function_name = false) {
	global $mw_action_hook_index;

	if (is_bool($function_name)) {
		$mw_action_hook_index = ($mw_action_hook_index);
		return $mw_action_hook_index;
	} else {
		if (!isset($mw_action_hook_index[$function_name])) {
			$mw_action_hook_index[$function_name] = array();
		}

		$mw_action_hook_index[$function_name][] = $next_function_name;

		//  $index .= ' ' . $function_name;
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
	//$l = parse_micrwober_tags($l, $options = false);

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

		echo 'data: ' . $i . rand() . rand() . rand() . rand() . rand() . rand() . "\n";

	}

	exit();
}

function parse_params($params) {
	$params2 = array();
	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
		unset($params2);
	}
	return $params;
}

$mw_var_storage = array();
function mw_var($key, $new_val = false) {
	global $mw_var_storage;
	$contstant = ($key);
	if ($new_val == false) {
		if (isset($mw_var_storage[$contstant]) != false) {
			return $mw_var_storage[$contstant];
		} else {
			return false;
		}
	} else {
		if (isset($mw_var_storage[$contstant]) == false) {
			$mw_var_storage[$contstant] = $new_val;
			return $new_val;
		}
	}
	return false;
}
