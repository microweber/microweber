<?php




/**
 * Saves data to any db table
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
 * @return array The database results
 */
function save($table, $data)
{
    return mw()->db->save($table, $data);

}


/**
 * Get data from any db table and filter it
 *
 * Function parameters:
 *  - you can filter the results by setting key=>value array with the name of the db fields
 *     $params['table'] - the name of the db table, it adds table prefix automatically
 * @since 0.1
 * @link http://microweber.com/docs/functions/get
 *
 * @param array|string $params
 * @return array The database results
 */
function get($params)
{
    return mw()->db->get($params);

}

function db_build_table($table_name, $fields_to_add, $column_for_not_drop = array())
{

    return mw()->db->build_table($table_name, $fields_to_add, $column_for_not_drop);

}

function db_get_table_name($assoc_name)
{

    $assoc_name = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name);
    return $assoc_name;
}


function delete_by_id($table, $id = 0, $field_name = 'id'){
    return mw()->db->delete_by_id($table, $id, $field_name);
}


function db_get_real_table_name($assoc_name)
{
    return mw()->db->real_table_name($assoc_name);

}


/**
 * Get Relative table name from a string
 *
 * @package Database
 * @subpackage Advanced
 * @param string $for string Your table name
 *
 * @param bool $guess_cache_group If true, returns the cache group instead of the table name
 *
 * @return bool|string
 * @example
 * <code>
 * $table = guess_table_name('content');
 * </code>
 */
function guess_table_name($for, $guess_cache_group = false)
{
    return mw()->db->guess_table_name($for, $guess_cache_group);


}


function db_escape_string($value)
{
    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
    $new = str_replace($search, $replace, $value);
    return $new;
}
