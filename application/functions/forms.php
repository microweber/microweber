<?php

if (!defined("MW_DB_TABLE_COUNTRIES")) {
	define('MW_DB_TABLE_COUNTRIES', MW_TABLE_PREFIX . 'countries');
}

action_hook('mw_db_init', 'mw_db_init_countries_table');

function mw_db_init_countries_table() {
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

	$table_sql = INCLUDES_PATH . 'install' . DS . 'countries.sql';

	import_sql_from_file($table_sql);

	cache_store_data(true, $function_cache_id, $cache_group = 'db');
	return true;
}

function countries_list() {

	$table = MW_DB_TABLE_COUNTRIES;

	$sql = "SELECT name as country_name from $table   ";

	$q = db_query($sql, __FUNCTION__ . crc32($sql), 'db');
	$res = array();
	if (isarr($q)) {
		foreach ($q as $value) {
			$res[] = $value['country_name'];
		}
		return $res;
	} else {
		return false;
	}

}

api_expose('post_form');
function post_form($data) {
	$table = MW_TABLE_PREFIX . 'forms';

	$adm = is_admin();

	$table = MODULE_DB_COMMENTS;
	mw_var('FORCE_SAVE', $table);

	if (isset($data['id'])) {
		if ($adm == false) {
			error('Error: Only admin can edit comments!');
		}

	}
	
	print json_encode($data);

}
