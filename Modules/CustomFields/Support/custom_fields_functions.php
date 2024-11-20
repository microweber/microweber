<?php

if (!function_exists('get_custom_field_by_id')) {
    function get_custom_field_by_id($id)
    {
        return mw()->fields_manager->getById($id);
    }
}

if (!function_exists('save_custom_field')) {
    function save_custom_field($data)
    {
        return mw()->fields_manager->save($data);
    }
}

if (!function_exists('delete_custom_field')) {
    function delete_custom_field($data)
    {
        return mw()->fields_manager->delete($data);
    }
}

if (!function_exists('make_custom_field')) {
    function make_custom_field($field_id = 0, $field_type = 'text', $settings = false)
    {
        return mw()->fields_manager->make($field_id, $field_type, $settings);
    }
}

if (!function_exists('custom_field_value')) {
    function custom_field_value($content_id, $field_name, $table = 'content')
    {
        return mw()->fields_manager->get_value($content_id, $field_name, $table);
    }
}

if (!function_exists('get_custom_fields')) {
    function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false)
    {
        if (isset($table) and !is_array($table) and intval($table) > 0) {
            $id = intval(intval($table));
            $table = 'content';
        }

        return mw()->fields_manager->get([
            'rel_type' => $table,
            'rel_id' => $id,
            'return_full' => $return_full,
            'name' => $field_for,
            'debug' => $debug,
            'type' => $field_type,
            'session_id' => $for_session
        ]);
    }
}
