<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */
if (!function_exists('get_table_prefix')) {
    function get_table_prefix()
    {
        return app()->database_manager->get_prefix();
    }
}

if (!function_exists('db_get')) {
    function db_get($table_name_or_params, $params = null)
    {
        return app()->database_manager->get($table_name_or_params, $params);
    }
}

/**
 * Saves data to any db table.
 *
 * Function parameters:
 *
 *     $table - the name of the db table, it adds table prefix automatically
 *     $data - key=>value array of the data you want to store
 *
 * @since 0.1
 * @link http://microweber.com/docs/functions/save
 *
 * @param $table
 * @param $data
 *
 * @return array The database results
 */
if (!function_exists('db_save')) {
    function db_save($table_name_or_params, $params = null)
    {
        return app()->database_manager->save($table_name_or_params, $params);
    }
}

if (!function_exists('db_delete')) {
    function db_delete($table_name, $id = 0, $field_name = 'id')
    {
        return app()->database_manager->delete_by_id($table_name, $id, $field_name);
    }
}

if (!function_exists('collection_to_array')) {
    function collection_to_array($data)
    {
        if (
            $data instanceof \Illuminate\Database\Eloquent\Collection
            or $data instanceof \Illuminate\Support\Collection
            or $data instanceof \Illuminate\Database\Eloquent\Model
        ) {
            return $data->toArray();
        }
        return $data;

    }
}