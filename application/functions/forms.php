<?php

/**
 * make_field
 *
 * @desc make_field
 * @access      public
 * @category    forms
 * @author      Microweber
 * @link        http://microweber.com
 * @param string $field_type
 * @param string $field_id
 * @param array $settings
 */

function make_field($field_id = 0, $field_type = 'text', $settings = false) {
	$CI = get_instance();
	if (is_array($field_id)) {
		if (!empty($field_id)) {
			$data = $field_id;
		}

	} else {
		if ($field_id != 0) {

			//print $field_id;

			$data = $CI -> core_model -> getById('table_custom_fields', $id = $field_id, $is_this_field = false);
			//p($data);
			//getById($table, $id = 0, $is_this_field = false)
			//exit('$field_id' . $field_id);
		}

	}

	if (is_array($data)) {
		if (!empty($data)) {

			$CI -> load -> vars(array('data' => $data));
			$field_type = $data['custom_field_type'];
		}

	}

	$dir = dirname(__FILE__);
	$dir = $dir . DS . 'custom_fields' . DS;
	$field_type = str_replace('..', '', $field_type);
	if ($settings == true) {
		$file = $dir . $field_type . '_settings.php';

	} else {
		$file = $dir . $field_type . '.php';

	}
	$CI -> load -> vars(array('data' => $data, 'field_type' => $field_type));

	$CI -> load -> vars($CI -> template);

	$layout = $CI -> load -> file($file, true);

	return $layout;

}

function save_field($data) {
	$id = user_id();
	if ($id == 0) {
		exit('Error: not logged in.');
	}
	$id = is_admin();
	if ($id == false) {
		exit('Error: not logged in as admin.');
	}
	$data =    get_instance() -> core_model -> addSlashesToArrayAndEncodeHtmlChars($data);
	$data =             	get_instance() -> core_model -> saveCustomField($data);

	return ($data);
	//exit
}

function remove_field($id) {
	$uid = user_id();
	if ($uid == 0) {
		exit('Error: not logged in.');
	}
	$uid = is_admin();
	if ($uid == false) {
		exit('Error: not logged in as admin.');
	}
	$id = intval($id);
	$data =             	get_instance() -> core_model -> deleteCustomFieldById($id);

	return ($data);

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
		
		
		$cf_data =             	get_instance() -> core_model -> saveCustomField($custom_field_data);
		
		
		
		//	$custom_field_data['to_table']  = 'table_forms';

	}

	p($add_enrty);

//	p($table);
	p($cf_data);
//	p($form_fields);

}
