<?php

api_expose_admin('save_dynamic_text');
function save_dynamic_text($data)
{
    if (!is_admin()) {
        return;
    }

    if (isset($data['id']) && $data['id'] == '0') {
        unset($data['id']);
    }
    $data['allow_html'] = true;
    $table = "dynamic_text_variables";
    return db_save($table, $data);
}

api_expose('get_dynamic_text');
function get_dynamic_text($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $params['table'] = "dynamic_text_variables";
    return db_get($params);
}

api_expose_admin('delete_dynamic_text');
function delete_dynamic_text($params)
{
    if (!is_admin()) {
        return;
    }
    if (isset($params['id'])) {
        $table = "dynamic_text_variables";
        $id = $params['id'];
        return db_delete($table, $id);
    }
}
