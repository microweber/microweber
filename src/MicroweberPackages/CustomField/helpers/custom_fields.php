<?php

api_expose_admin('fields/reorder');
api_expose_admin('fields/delete');
api_expose_admin('fields/make');
api_expose_admin('fields/save');

function get_custom_field_by_id($id)
{
    return mw()->fields_manager->getById($id);
}

api_bind_admin('fields/save', 'save_custom_field');
function save_custom_field($data)
{
    return mw()->fields_manager->save($data);
}

function delete_custom_field($data)
{
    return mw()->fields_manager->delete($data);
}

api_bind_admin('fields/make', 'make_custom_field');
function make_custom_field($field_id = 0, $field_type = 'text', $settings = false)
{
    return mw()->fields_manager->make($field_id, $field_type, $settings);
}

function custom_field_value($content_id, $field_name, $table = 'content')
{
    return mw()->fields_manager->get_value($content_id, $field_name, $table);
}

function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false)
{
    if (isset($table)  and !is_array($table) and intval($table) > 0) {
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

api_bind_admin('fields/delete', function ($data) {
    return mw()->fields_manager->delete($data);
});

api_bind_admin('fields/reorder', function ($data) {
    return mw()->fields_manager->reorder($data);
});
