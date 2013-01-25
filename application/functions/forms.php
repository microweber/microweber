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

function get_form_data($params) {
	$table = MW_DB_TABLE_FORMS;
	$params['table'] = $table;

	return get($params);
}

api_expose('post_form');
function post_form($params) {

	$adm = is_admin();

	$table = MW_DB_TABLE_FORMS;
	mw_var('FORCE_SAVE', $table);

	if (isset($params['id'])) {
		if ($adm == false) {
			error('Error: Only admin can edit forms!');
		}
	}
	$for = 'module';
	if (isset($params['for'])) {
		$for = $params['for'];
	}

	if (isset($params['for_id'])) {
		$for_id = $params['for_id'];
	} else if (isset($params['data-id'])) {
		$for_id = $params['data-id'];
	} else if (isset($params['id'])) {
		$for_id = $params['id'];
	}

	if ($for == 'module') {
		$form_name = get_option('form_name', $for_id);
	}
	if ($form_name == false) {
		$form_name = $for_id;
	}

	//$for_id =$params['id'];
	if (isset($params['to_table_id'])) {
		$for_id = $params['to_table_id'];
	}
	$to_save = array();
	$fields_data = array();
	$more = get_custom_fields($for, $for_id, 1);
	if (!empty($more)) {
		foreach ($more as $item) {
			if (isset($item['custom_field_name'])) {
				$cfn = ($item['custom_field_name']);
				$cfn2 = str_replace(' ', '_', $cfn);
				$fffound = false;
				if (isset($params[$cfn2])) {
					$fields_data[$cfn2] = $params[$cfn2];
					$fffound = 1;
				} elseif (isset($params[$cfn])) {
					$fields_data[$cfn] = $params[$cfn];
					$fffound = 1;
				}

			}
		}
	}
	$to_save['form_name'] = $form_name;
	$to_save['to_table_id'] = $form_name;
	$to_save['to_table'] = $for;
	$to_save['custom_fields'] = $fields_data;
	if (isset($params['module_name'])) {
		$to_save['module_name'] = $params['module_name'];
	}

	$save = save_data($table, $to_save);

	return ($save);

}
