<?

if (!defined("MW_DB_TABLE_NOTIFICATIONS")) {
	define('MW_DB_TABLE_NOTIFICATIONS', MW_TABLE_PREFIX . 'notifications');
}

action_hook('mw_db_init_default', 'mw_db_init_notifications_table');

function mw_db_init_notifications_table() {
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, 'db');

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table_name = MW_DB_TABLE_NOTIFICATIONS;

	$fields_to_add = array();

	$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('created_by', 'int(11) default NULL');
	$fields_to_add[] = array('edited_by', 'int(11) default NULL');
	$fields_to_add[] = array('data_type', 'TEXT default NULL');
	$fields_to_add[] = array('title', 'longtext default NULL');
	$fields_to_add[] = array('description', 'TEXT default NULL');
	$fields_to_add[] = array('content', 'TEXT default NULL');
	$fields_to_add[] = array('module', 'TEXT default NULL');

	$fields_to_add[] = array('rel', 'TEXT default NULL');
	$fields_to_add[] = array('rel_id', 'TEXT default NULL');
	$fields_to_add[] = array('notif_count', 'int(11) default 1');

	$fields_to_add[] = array('is_read', "char(1) default 'n'");

	set_db_table($table_name, $fields_to_add);

	db_add_table_index('rel', $table_name, array('rel(55)'));
	db_add_table_index('rel_id', $table_name, array('rel_id(55)'));

	cache_save(true, $function_cache_id, $cache_group = 'db');
	return true;

}

api_expose('post_notification');

function post_notification($params) {

	$params = parse_params($params);

	// if (!isset($params['rel']) and isset($params['module']) and trim($params['module']) != '') {
	// $params['rel'] = 'table_modules';
	// $params['rel_id'] = $params['module'];
	// }

	//$adm = is_admin();

	$table = MW_DB_TABLE_NOTIFICATIONS;
	mw_var('FORCE_SAVE', $table);

	if (!isset($params['rel']) or !isset($params['rel_id'])) {
		return ('Error: invalid data you must send rel and rel_id as params for post_notification function');
	}
	$old = date("Y-m-d H:i:s", strtotime('-30 days'));
	$cleanup = "delete from $table where created_on < '{$old}'";
	db_q($cleanup);
	cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');
	$data = save_data($table, $params);
	return $data;
}

function mark_notifications_as_read($module) {

	if (($module) != false and $module != '') {

		$table = MW_DB_TABLE_NOTIFICATIONS;

		mw_var('FORCE_SAVE', $table);

		$get_params = array();
		$get_params['table'] = 'table_notifications';
		$get_params['is_read'] = 'n';
		$get_params['fields'] = 'id';
		$get_params['module'] = db_escape_string($module);

		$data = get_notifications($get_params);
		if (isarr($data)) {
			foreach ($data as $value) {
				$save['is_read'] = 'y';
				$save['id'] = $value['id'];
				$save['table'] = 'table_notifications';
				save('table_notifications', $save);
			}
		}

		cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');
		return $data;
	}

}

function read_notification($id) {
	$params = array();
	$params['id'] = intval($id);
	$params['one'] = true;

	$get = get_notifications($params);

	if ($get != false and $get['is_read'] == 'n') {
		$save = array();
		$save['id'] = $get['id'];
		$save['is_read'] = 'y';
		$table = MW_DB_TABLE_NOTIFICATIONS;
		mw_var('FORCE_SAVE', $table);
		$data = save_data($table, $save);
	}

	return $get;
}

function get_notification($id) {
	$params = array();
	$params['id'] = intval($id);
	$params['one'] = true;

	$get = get_notifications($params);
	return $get;
}

api_expose('notifications_reset');
function notifications_reset() {

	$is_admin = is_admin();
	if ($is_admin == false) {
		error('Error: not logged in as admin.');
	}

	$table = MW_DB_TABLE_NOTIFICATIONS;

	$q = "update $table set is_read='n'";
	db_q($q);
	cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');

	return true;

}

api_expose('delete_notification');
function delete_notification($id) {

	$is_admin = is_admin();
	if ($is_admin == false) {
		error('Error: not logged in as admin.');
	}

	$table = MW_DB_TABLE_NOTIFICATIONS;

	db_delete_by_id($table, intval($id), $field_name = 'id');

	cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');

	return true;

}

function get_notifications($params) {
	$params = parse_params($params);

	// if (!isset($params['rel']) and isset($params['module']) and trim($params['module']) != '') {
	// $params['rel'] = 'table_modules';
	// $params['rel_id'] = $params['module'];
	// }
	//

	$table = MW_DB_TABLE_NOTIFICATIONS;
	$params['table'] = $table;

	$params = get($params);
	return $params;
}

$_mw_email_transport_object = false;
function email_get_transport_object() {

	global $_mw_email_transport_object;

	if (is_object($_mw_email_transport_object)) {
		return $_mw_email_transport_object;
	}

	$email_advanced = get_option('email_transport', 'email');
	if ($email_advanced == false or $email_advanced == '') {
		$email_advanced = 'php';
	}

	$transport_type = trim($email_advanced);

	try {
		$_mw_email_obj = new mw\email\sender($transport_type);
		$_mw_email_transport_object = $_mw_email_obj;
		return $_mw_email_obj;
	} catch (Exception $e) {
		return ($e -> getMessage());
	}

	return false;

}

function mw_mail($to, $subject, $message, $add_hostname_to_subject = false) {

	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);
	$cache_group = "notifications/email";
	$cache_content = cache_get_content($function_cache_id, $cache_group);

	if (($cache_content) != false) {

		return $cache_content;
	}

	$res = email_get_transport_object();
	if (is_object($res)) {

		$email_from = get_option('email_from', 'email');
		if ($email_from == false or $email_from == '') {
			//return mw_error('You must set your email address first!');
		} else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
			//return mw_error("E-mail is not valid");
		}

		if ($add_hostname_to_subject != false) {
			$subject = '[' . site_hostname() . '] ' . $subject;
		}

		if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
			//$res -> debug = 1;
			$res -> send($to, $subject, $message);
			cache_save(true, $function_cache_id, $cache_group);
			return true;
		} else {
			return false;
		}

	}
}

api_expose('email_send_test');
function email_send_test($params) {

	$is_admin = is_admin();
	if ($is_admin == false) {
		error('Error: not logged in as admin.');
	}
	$res = email_get_transport_object();
	if (is_object($res)) {

		$email_from = get_option('email_from', 'email');
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

}
