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
	// $fields = (array_change_key_case ( $fields, CASE_LOWER ));
	return true;

	//print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
}

function countries_list() {
  
	$table = MW_DB_TABLE_COUNTRIES;

	$sql = "SELECT country_name from $table   ";

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

function save_form_data($data) {
	$CI = get_instance();
	 

	$table = $cms_db_tables['table_forms'];

	$db_system_fields = $CI -> core_model -> mapArrayToDatabaseTable($table, $data);

	$form_fields = array_diff($data, $db_system_fields);

	$add_enrty = save_data($table, $db_system_fields);

	$fields_data = array();

	foreach ($form_fields as $k => $v) {
		$custom_field_data = array();
		$custom_field_data['to_table'] = 'table_forms';
		$custom_field_data['to_table_id'] = $add_enrty;
		$custom_field_data['custom_field_name'] = $k;
		$custom_field_data['custom_field_value'] = $v;
		$custom_field_data['field_for'] = $db_system_fields['form_title'];

		$cf_data =      get_instance() -> core_model -> saveCustomField($custom_field_data);

		//	$custom_field_data['to_table']  = 'table_forms';
	}

	p($add_enrty);

	//	p($table);
	p($cf_data);
	//	p($form_fields);
}
