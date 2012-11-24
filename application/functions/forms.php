<?php

function countries_list() {

	$table = c('db_tables');
	// ->'table_content';
	$table = $table['table_country'];

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
	global $cms_db_tables;

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

		$cf_data =    get_instance() -> core_model -> saveCustomField($custom_field_data);

		//	$custom_field_data['to_table']  = 'table_forms';
	}

	p($add_enrty);

	//	p($table);
	p($cf_data);
	//	p($form_fields);
}
