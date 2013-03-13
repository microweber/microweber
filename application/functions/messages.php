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
	$fields_to_add[] = array('to_table', 'TEXT default NULL');
	$fields_to_add[] = array('to_table_id', 'TEXT default NULL');
	$fields_to_add[] = array('notif_count', 'int(11) default 1');

	$fields_to_add[] = array('is_read', "char(1) default 'n'");

	set_db_table($table_name, $fields_to_add);

	db_add_table_index('to_table', $table_name, array('to_table(55)'));
	db_add_table_index('to_table_id', $table_name, array('to_table_id(55)'));

	cache_save(true, $function_cache_id, $cache_group = 'db');
	return true;

}

api_expose('post_notification');

function post_notification($params) {

	$params = parse_params($params);

	if (!isset($params['to_table']) and isset($params['module']) and trim($params['module']) != '') {
		$params['to_table'] = 'table_modules';
		$params['to_table_id'] = $params['module'];
	}

	//$adm = is_admin();

	$table = MW_DB_TABLE_NOTIFICATIONS;
	mw_var('FORCE_SAVE', $table);

	if (!isset($params['to_table']) or !isset($params['to_table_id'])) {
		error('Error: invalid data you must send to_table and to_table_id as params for post_notification function');
	}

	$data = save_data($table, $params);
	return $data;
}

function get_notification($params) {
	$params = parse_params($params);
	
	if (!isset($params['to_table']) and isset($params['module']) and trim($params['module']) != '') {
		$params['to_table'] = 'table_modules';
		$params['to_table_id'] = $params['module'];
	}
	
	
	$table = MW_DB_TABLE_NOTIFICATIONS;
	$params['table'] = $table;

	$params = get($params);
	return $params;
}
