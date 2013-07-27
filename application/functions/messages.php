<?php

/*
api_expose('mw('Mw\Notifications')->save');

function mw('Mw\Notifications')->save($params) {

	$params = parse_params($params);

	// if (!isset($params['rel']) and isset($params['module']) and trim($params['module']) != '') {
	// $params['rel'] = 'modules';
	// $params['rel_id'] = $params['module'];
	// }

	//$adm = is_admin();

	$table = MW_DB_TABLE_NOTIFICATIONS;
	mw_var('FORCE_SAVE', $table);

	if (!isset($params['rel']) or !isset($params['rel_id'])) {
		return ('Error: invalid data you must send rel and rel_id as params for mw('Mw\Notifications')->save function');
	}
	$old = date("Y-m-d H:i:s", strtotime('-30 days'));
	$cleanup = "delete from $table where created_on < '{$old}'";
	\mw('db')->q($cleanup);

	if(isset($params['replace'])){
		if(isset($params['module']) and isset($params['rel']) and isset($params['rel_id'])){
			unset($params['replace']);
			$rel1 = mw('db')->escape_string($params['rel']);
			$module1 = mw('db')->escape_string($params['module']);
			$rel_id1 = mw('db')->escape_string($params['rel_id']);
			$cleanup = "delete from $table where rel='{$rel1}' and module='{$module1}' and rel_id='{$rel_id1}'";
			\mw('db')->q($cleanup);



		}

	}





	mw('cache')->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

	$data = save($table, $params);
	return $data;
}*/

/*
function mw('Mw\Notifications')->delete_for_module($module) {

	if (($module) != false and $module != '') {

		$table = MW_DB_TABLE_NOTIFICATIONS;

		mw_var('FORCE_SAVE', $table);

		 $get_params = array();
		$get_params['table'] = 'table_notifications';
		$get_params['fields'] = 'id';
		$get_params['module'] = mw('db')->escape_string($module);

		$data = mw('Mw\Notifications')->get($get_params);
		if(is_array($data )){
		  $ids = mw('format')->array_values($data);
		  $idsi = implode(',',$ids);
		  $cleanup = "delete from $table where id IN ({$idsi})";
		  \mw('db')->q($cleanup);
		}

		mw('cache')->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
		return true;
	}
}*/
/*
function mw('Mw\Notifications')->mark_as_read($module) {

	if (($module) != false and $module != '') {

		$table = MW_DB_TABLE_NOTIFICATIONS;

		mw_var('FORCE_SAVE', $table);

		$get_params = array();
		$get_params['table'] = 'table_notifications';
		$get_params['is_read'] = 'n';
		$get_params['fields'] = 'id';
		$get_params['module'] = mw('db')->escape_string($module);

		$data = mw('Mw\Notifications')->get($get_params);
		if (is_array($data)) {
			foreach ($data as $value) {
				$save['is_read'] = 'y';
				$save['id'] = $value['id'];
				$save['table'] = 'table_notifications';
				save('table_notifications', $save);

			}
		}

		mw('cache')->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
		return $data;
	}
}*/
/*
function mw('Mw\Notifications')->read($id) {
	$params = array();
	$params['id'] = trim($id);
	$params['one'] = true;

	$get = mw('Mw\Notifications')->get($params);

	if ($get != false and isset($get['is_read']) and $get['is_read'] == 'n') {
		$save = array();
		$save['id'] = $get['id'];
		$save['is_read'] = 'y';
		$table = MW_DB_TABLE_NOTIFICATIONS;
		mw_var('FORCE_SAVE', $table);
		$data = \mw('db')->save($table, $save);
		mw('cache')->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

	}

	return $get;
}*/
/*
function mw('Mw\Notifications')->get_by_id($id) {
	$params = array();

	if ($id != false) {
		if (substr(strtolower($id), 0, 4) == 'log_') {

		}

		$params['id'] = mw('db')->escape_string($id);
		$params['one'] = true;

		$get = mw('Mw\Notifications')->get($params);
		return $get;

	}
}*/
/*
api_expose('mw('Mw\Notifications')->reset');
function mw('Mw\Notifications')->reset() {

	$is_admin = is_admin();
	if ($is_admin == false) {
		error('Error: not logged in as admin.'.__FILE__.__LINE__);
	}

	$table = MW_DB_TABLE_NOTIFICATIONS;

	$q = "update $table set is_read='n'";
	\mw('db')->q($q);
	mw('cache')->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

	return true;

}*/
/*
api_expose('delete_notification');
function mw('Mw\Notifications')->delete($id) {

	$is_admin = is_admin();
	if ($is_admin == false) {
		error('Error: not logged in as admin.'.__FILE__.__LINE__);
	}

	$table = MW_DB_TABLE_NOTIFICATIONS;

	\mw('db')->delete_by_id($table, intval($id), $field_name = 'id');

	mw('cache')->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

	return true;

}*/
/*
function mw('Mw\Notifications')->get($params) {
	$params = parse_params($params);

	// if (!isset($params['rel']) and isset($params['module']) and trim($params['module']) != '') {
	// $params['rel'] = 'modules';
	// $params['rel_id'] = $params['module'];
	// }
	//
	$return = array();
	$is_sys_log = false;
	if (isset($params['id'])) {
		$is_log = substr(strtolower($params['id']), 0, 4);
		if ($is_log == 'log_') {
			$is_sys_log = 1;
			$is_log_id = str_ireplace('log_', '', $params['id']);
		$log_entr = get_log_entry($is_log_id);
			if($log_entr !=false and isset($params['one'])){
				return $log_entr;

			} else if($log_entr !=false ) {
			$return[] = $log_entr;
			}
			// d($is_log_id);
		}

	}
	if ($is_sys_log == false) {
		$table = MW_DB_TABLE_NOTIFICATIONS;
		$params['table'] = $table;

		$return = \mw('db')->get($params);
	}
	return $return;
}*/

$_mw_email_transport_object = false;
function email_get_transport_object() {

	global $_mw_email_transport_object;

	if (is_object($_mw_email_transport_object)) {
		return $_mw_email_transport_object;
	}

	$email_advanced = mw('option')->get('email_transport', 'email');
	if ($email_advanced == false or $email_advanced == '') {
		$email_advanced = 'php';
	}

	$transport_type = trim($email_advanced);

	try {
		$_mw_email_obj = new \mw\email\Sender($transport_type);
		$_mw_email_transport_object = $_mw_email_obj;
		return $_mw_email_obj;
	} catch (Exception $e) {
		return ($e -> getMessage());
	}

	return false;

}
/*
function \mw\email\Sender::send($to, $subject, $message, $add_hostname_to_subject = false, $no_cache = false, $cc = false) {

	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);
	$cache_group = "notifications/email";
	$cache_content = mw('cache')->get($function_cache_id, $cache_group);

	if ($no_cache == false and ($cache_content) != false) {

		return $cache_content;
	}

	$res = email_get_transport_object();
	if (is_object($res)) {

		$email_from = mw('option')->get('email_from', 'email');
		if ($email_from == false or $email_from == '') {
			//return mw_error('You must set your email address first!');
		} else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
			//return mw_error("E-mail is not valid");
		}

		if ($add_hostname_to_subject != false) {
			$subject = '[' . site_hostname() . '] ' . $subject;
		}

		if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
			//  $res -> debug = 1;
			if (isset($cc) and ($cc) != false and (filter_var($cc, FILTER_VALIDATE_EMAIL))) {
				$res -> setCc($cc);
			}

			$res -> send($to, $subject, $message);
			mw('cache')->save(true, $function_cache_id, $cache_group);
			return true;
		} else {
			return false;
		}

	}
}*/
/*
api_expose('email_send_test');
function email_send_test($params) {

	$is_admin = is_admin();
	if ($is_admin == false) {
		error('Error: not logged in as admin.'.__FILE__.__LINE__);
	}
	$res = email_get_transport_object();
	if (is_object($res)) {

		$email_from = mw('option')->get('email_from', 'email');
		if ($email_from == false or $email_from == '') {
			//return mw_error('You must set your email address first!');
		} else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
			//return mw_error("E-mail is not valid");
		}
		if (isset($params['to']) and (filter_var($params['to'], FILTER_VALIDATE_EMAIL))) {
			$to = $params['to'];
			$subject = "Test mail";

			if (isset($params['subject'])) {
				$subject = $params['subject'];
			}

			$message = "Hello! This is a simple email message.";
			$res -> debug = 1;
			$res -> send($to, $subject, $message);
		} else {
			return mw_error("Test E-mail is not valid");
		}

	}

	return true;

}*/
