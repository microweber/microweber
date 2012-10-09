<?php

function create_mw_default_options() {

	$function_cache_id = __FUNCTION__;

	$cache_content = cache_get_content($function_cache_id, $cache_group = 'options');
	if (($cache_content) == '--true--') {
		return true;
	}

	define('FORCE_SAVE', 1);
	$datas = array();

	$data = array();
	$data['option_group'] = 'website';
	$data['option_key'] = 'website_title';
	$data['option_value'] = 'My website';
	$data['position'] = '1';
	$datas[] = $data;

	$data = array();
	$data['option_group'] = 'website';
	$data['option_key'] = 'website_description';
	$data['option_value'] = 'My website\'s description';
	$data['position'] = '2';
	$datas[] = $data;

	$data = array();
	$data['option_group'] = 'website';
	$data['option_key'] = 'curent_template';
	$data['option_value'] = 'lab';
	$data['position'] = '3';
	$datas[] = $data;

	$data = array();
	$data['option_group'] = 'users';
	$data['option_key'] = 'enable_user_registration';
	$data['option_value'] = '0';
	$datas[] = $data;

	foreach ($datas as $value) {
		set_default_option($value);
	}

	cache_store_data('--true--', $function_cache_id, $cache_group = 'options');

	return true;

}

function set_default_option($data) {

	if (is_array($data)) {
		if (!isset($data['option_group'])) {
			$data['option_group'] = 'other';
		}

		if (isset($data['option_key'])) {
			$check = get_option($data['option_key'], $option_group = $data['option_group'], $return_full = false, $orderby = false);
			if ($check == false) {
				save_option($data);
			}
		}
	} else {
		error('set_default_option $data param must be array');
	}
}

function option_get($key, $option_group = false, $return_full = false, $orderby = false) {
	return get_option($key, $option_group, $return_full, $orderby);
}

function get_option_groups() {
	$table = c('db_tables');
	// ->'table_options';
	$table = $table['table_options'];

	$q = "select option_group from $table where module IS NULL and option_group IS NOT NULL group by option_group order by position ASC ";
	$function_cache_id = __FUNCTION__ . crc32($q);
	$res1 = false;
	$res = db_query($q, $cache_id = $function_cache_id, $cache_group = 'options');
	if (is_array($res) and !empty($res)) {
		$res1 = array();
		foreach ($res as $item) {
			$res1[] = $item['option_group'];
		}
	}
	return $res1;
}

function get_option($key, $option_group = false, $return_full = false, $orderby = false) {
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, $cache_group = 'options');
	if (($cache_content) == '--false--') {
		return false;
	}
	// $cache_content = false;
	if (($cache_content) != false) {

		return $cache_content;
	} else {

		$table = c('db_tables');
		// ->'table_options';
		$table = $table['table_options'];

		if ($orderby == false) {

			$orderby[0] = 'created_on';

			$orderby[1] = 'DESC';
		}

		$data = array();
		// $data ['debug'] = 1;
		if (is_array($key)) {
			$data = $key;
		} else {
			$data['option_key'] = $key;
		}

		if ($option_group != false) {
			$data['option_group'] = $option_group;
		}
		$data['limit'] = 1;
		$get = db_get($table, $data, $cache_group = 'options');

		if (!empty($get)) {

			if ($return_full == false) {

				$get = $get[0]['option_value'];

				cache_store_data($get, $function_cache_id, $cache_group = 'options');

				return $get;
			} else {

				$get = $get[0];

				cache_store_data($get, $function_cache_id, $cache_group = 'options');

				return $get;
			}
		} else {
			cache_store_data('--false--', $function_cache_id, $cache_group = 'options');

			return FALSE;
		}
	}
}

if (is_admin() != false) {
	api_expose('save_option');
}
function save_option($data) {
	$is_admin = is_admin();
	if ($is_admin == false or !defined('FORCE_SAVE')) {
		//error('Error: not logged in as admin.');
	}
	// p($_POST);
	if ($data) {
		if (!isset($data['id']) or intval($data['id']) == 0) {
			if ($data['option_key'] and $data['option_group']) {
				delete_option_by_key($data['option_key'], $data['option_group']);
			}
		}
		if (strval($data['option_key']) != '') {

			$cms_db_tables = c('db_tables');

			$table = $cms_db_tables['table_options'];

			// $data ['debug'] = 1;
			$save = save_data($table, $data);
			cache_clean_group('options');
			return $save;
		}
	}
}

function delete_option_by_key($key, $option_group = false) {
	$key = mysql_real_escape_string($key);
	$cms_db_tables = c('db_tables');

	$table = $cms_db_tables['table_options'];

	cache_clean_group('options');
	if ($option_group != false) {
		$option_group = mysql_real_escape_string($option_group);
		$option_group_q1 = "and option_group='{$option_group}'";
	}
	// $save = $this->saveData ( $table, $data );
	$q = "delete from $table where option_key='$key' $option_group_q1 ";

	db_q($q);

	return true;
}
