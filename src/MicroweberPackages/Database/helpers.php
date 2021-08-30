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

if (!function_exists('db_query_parse_compare_sign_value')) {
    function db_query_parse_compare_sign_value($value)
    {
        $compare_sign = '=';
        $compare_value = $value;

        if (stristr($value, '[lt]')) {
            $compare_sign = '<';
            $value = str_replace('[lt]', '', $value);
        } elseif (stristr($value, '[lte]')) {
            $compare_sign = '<=';
            $value = str_replace('[lte]', '', $value);
        } elseif (stristr($value, '[st]')) {
            $compare_sign = '<';
            $value = str_replace('[st]', '', $value);
        } elseif (stristr($value, '[ste]')) {
            $compare_sign = '<=';
            $value = str_replace('[ste]', '', $value);
        } elseif (stristr($value, '[gt]')) {
            $compare_sign = '>';
            $value = str_replace('[gt]', '', $value);
        } elseif (stristr($value, '[gte]')) {
            $compare_sign = '>=';
            $value = str_replace('[gte]', '', $value);
        } elseif (stristr($value, '[mt]')) {
            $compare_sign = '>';
            $value = str_replace('[mt]', '', $value);
        } elseif (stristr($value, '[md]')) {
            $compare_sign = '>';
            $value = str_replace('[md]', '', $value);
        } elseif (stristr($value, '[mte]')) {
            $compare_sign = '>=';
            $value = str_replace('[mte]', '', $value);
        } elseif (stristr($value, '[mde]')) {
            $compare_sign = '>=';
            $value = str_replace('[mde]', '', $value);
        } elseif (stristr($value, '[neq]')) {
            $compare_sign = '!=';
            $value = str_replace('[neq]', '', $value);
        } elseif (stristr($value, '[eq]')) {
            $compare_sign = '=';
            $value = str_replace('[eq]', '', $value);
        } elseif (stristr($value, '[int]')) {
            $value = str_replace('[int]', '', $value);
            $value = intval($value);
        } elseif (stristr($value, '[is]')) {
            $compare_sign = '=';
            $value = str_replace('[is]', '', $value);
        } elseif (stristr($value, '[like]')) {
            $compare_sign = 'LIKE';
            $value = str_replace('[like]', '', $value);
            $compare_value = '%' . $value . '%';
        } elseif (stristr($value, '[not_like]')) {
            $value = str_replace('[not_like]', '', $value);
            $compare_sign = 'NOT LIKE';
            $compare_value = '%' . $value . '%';
        } elseif (stristr($value, '[is_not]')) {
            $value = str_replace('[is_not]', '', $value);
            $compare_sign = 'NOT LIKE';
            $compare_value = '%' . $value . '%';
        } elseif (stristr($value, '[in]')) {
            $value = str_replace('[in]', '', $value);
            $compare_sign = 'in';
        } elseif (stristr($value, '[not_in]')) {
            $value = str_replace('[not_in]', '', $value);
            $compare_sign = 'not_in';
        } elseif (strtolower($value) == '[null]') {
            $value = str_replace('[null]', '', $value);
            $compare_sign = 'null';
        } elseif (strtolower($value) == '[not_null]') {
            $value = str_replace('[not_null]', '', $value);
            $compare_sign = 'not_null';
        }

        return ['compare_sign'=>$compare_sign,'compare_value'=>$compare_value,'value'=>$value];
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
