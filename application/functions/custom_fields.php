<?php

function get_custom_field_by_id($field_id) {

    return \CustomFields::get_by_id($field_id);


}

function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false) {
    return \CustomFields::get($table, $id, $return_full, $field_for, $debug , $field_type , $for_session );
}

/*document_ready('test_document_ready_api');

 function test_document_ready_api($layout) {

 //   $layout = modify_html($layout, $selector = '.editor_wrapper', 'append', 'ivan');
 //$layout = modify_html2($layout, $selector = '<div class="editor_wrapper">', '');
 return $layout;
 }*/

/**
 * make_custom_field
 *
 * @desc make_custom_field
 * @access      public
 * @category    forms
 * @author      Microweber
 * @link        http://microweber.com
 * @param string $field_type
 * @param string $field_id
 * @param array $settings
 */
api_expose('make_custom_field');
function custom_field_names_for_table($table) {
    return \CustomFields::names_for_table($table);
}

function make_default_custom_fields($rel, $rel_id, $fields_csv_str) {
    return \CustomFields::make_default($rel, $rel_id, $fields_csv_str);
}

function make_custom_field($field_id = 0, $field_type = 'text', $settings = false) {
    return \CustomFields::make_field($field_id, $field_type, $settings);
}

api_expose('save_custom_field');

function save_custom_field($data) {
    return \CustomFields::save($data);
}

api_expose('reorder_custom_fields');

function reorder_custom_fields($data) {
    return \CustomFields::reorder($data);
}

api_expose('remove_field');

function remove_field($id) {
    return \CustomFields::delete($id);
}

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
    return \CustomFields::make($field_id, $field_type, $settings );

}
