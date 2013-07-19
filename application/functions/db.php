<?php

/**
 * Get items from the database
 *
 * You can use this handy function to get whatever you need from any db table.
 *
 * @params
 *
 * *You can pass those parameters in order to filter the results*
 *  You can also use all defined database fields as parameters
 *
 * .[params-table]
 *|-----------------------------------------------------------------------------
 *| Parameter        | Description      | Values
 *|------------------------------------------------------------------------------
 *| from            | the name of the db table, without prefix | ex. users, content, categories,etc
 *| table        | same as above |
 *| debug            | prints debug information  | true or false
 *| orderby        | you can order by any field in your table  | ex. get("table=content&orderby=id desc")
 *| order_by        | same as above  |
 *| one            | if set returns only the 1st result |
 *| count            | if set returns results count |  ex. get("table=content&count=true")
 *| limit            | limit the results |  ex. get("table=content&limit=5")
 *| curent_page    | get the current page by limit offset |  ex. get("table=content&limit=5&curent_page=2")
 *
 *
 * @param string|array $params parameters for the DB
 * @param string $params['table'] the table name ex. content
 * @param string $params['debug'] if true print the sql
 * @param string $params['cache_group'] sets the cache folder to use to cache the query result
 * @param string $params['no_cache']  if true it will no cache the sql
 * @param string $params['count']  if true it will return results count
 * @param string $params['page_count']  if true it will return pages count
 * @param string|array $params['limit']  if set it will limit the results
 *
 * @function get
 * @return mixed Array with data or false or integer if page_count is set
 *
 *
 *
 * @example
 * <code>
 * //get content
 *  $results = get("table=content&is_active=y");
 * </code>
 *
 * @example
 *  <code>
 *  //get users
 *  $results = get("table=users&is_admin=n");
 * </code>
 *
 * @package Database
 */
function get($params)
{
    return \mw\Db::get($params);
}



/**
 * Get table row by id
 *
 * It returns full db row from a db table
 *
 * @param string $table Your table
 * @param int|string $id The id to get
 * @param string $field_name You can set custom column to get by it, default is id
 *
 * @return array|bool|mixed
 * @example
 * <code>
 * //get content with id 5
 * $cont = db_get_id('content', $id=5);
 * </code>
 *
 * @package Database
 * @subpackage Advanced
 */
function db_get_id($table, $id = 0, $field_name = 'id')
{

    return \mw\Db::get_by_id($table, $id , $field_name );
}

/**
 * Generic save data function, it saves data to the database
 *
 * @param $table
 * @param $data
 * @param bool $data_to_save_options
 * @return string|int The id of the saved row.
 *
 * @example
 * <code>
 * $table = MW_TABLE_PREFIX.'content';
 * $data = array();
 * $data['id'] = 0; //if 0 will create new content
 * $data['title'] = 'new title';
 * $data['content'] = '<p>Something</p>';
 * $save = save($table, $data);
 * </code>
 * @package Database
 */
function save($table, $data, $data_to_save_options = false)
{
    return \mw\Db::save($table, $data, $data_to_save_options);

}
/**
 * Same as save()
 * @see save()
 * @package Database
 * @subpackage Advanced
 */
function save_data($table, $data, $data_to_save_options = false)
{

    return \mw\Db::save($table, $data, $data_to_save_options);
}

/**
 * Get last id from a table
 *
 * @desc Get last inserted id from a table, you must have 'id' column in it.
 * @package Database
 * @param $table
 * @return bool|int
 *
 * @example
 * <pre>
 * $table_name = MW_TABLE_PREFIX . 'content';
 * $id = db_last_id($table_name);
 * </pre>
 *
 */
function db_last_id($table)
{

    //  $db = new DB(c('db'));
    return \mw\Db::last_id($table);

}



/**
 * Performs a query without returning a result
 *
 * Useful if you want to preform table updates or deletes without the need to see the result
 *
 *
 * @param string $q Your SQL query
 * @param bool|array $connection_settigns
 * @return array|bool|mixed
 * @package Database
 * @uses db_query
 *
 *
 * @example
 *  <code>
 *  //make plain query to the db.
 * 	$table = MW_TABLE_PREFIX.'content';
 *  $sql = "update $table set title='new' WHERE id=1 ";
 *  $q = db_q($sql);
 * </code>
 *
 */
function db_q($q, $connection_settigns = false)
{

    return \mw\Db::q($q, $connection_settigns);

}

/**
 * Executes plain query in the database.
 *
 * You can use this function to make queries in the db by writing your own sql
 * The results are returned as array or `false` if nothing is found
 *
 *
 * @note Please ensure your variables are escaped before calling this function.
 * @package Database
 * @function db_query
 * @desc Executes plain query in the database.
 *
 * @param string $q Your SQL query
 * @param string|bool $cache_id It will save the query result in the cache. Set to false to disable
 * @param string|bool $cache_group Stores the result in certain cache group. Set to false to disable
 * @param bool $only_query If set to true, will perform only a query without returning a result
 * @param array|bool $connection_settigns
 * @return array|bool|mixed
 *
 * @example
 *  <code>
 *  //make plain query to the db
 * $table = MW_TABLE_PREFIX.'content';
 *    $sql = "SELECT id FROM $table WHERE id=1   ORDER BY updated_on DESC LIMIT 0,1 ";
 *  $q = db_query($sql, $cache_id=crc32($sql),$cache_group= 'content/global');
 *
 * </code>
 *
 *
 *
 */
function db_query($q, $cache_id = false, $cache_group = 'global', $only_query = false, $connection_settigns = false)
{
     return \mw\Db::query($q, $cache_id, $cache_group, $only_query, $connection_settigns);

}





/**
 * Returns an array that contains only keys that has the same names as the table fields from the database
 *
 * @param string
 * @param  array
 * @return array
 * @package Database
 * @subpackage Advanced
 * @example
 * <code>
 * $table = MW_TABLE_PREFIX.'content';
 * $data = array();
 * $data['id'] = 1;
 * $data['non_ex'] = 'i do not exist and will be removed';
 * $criteria = map_array_to_database_table($table, $data);
 * var_dump($criteria);
 * </code>
 */
function map_array_to_database_table($table, $array)
{
    return \mw\Db::map_array_to_table($table, $array);

}




/**
 * Keep a database query log
 *
 * @param string $q If its string it will add query to the log, its its bool true it will return the log entries as array;
 *
 * @return array
 * @example
 * <code>
 * //add query to the db log
 * db_query_log("select * from my_table");
 *
 * //get the query log
 * $queries = db_query_log(true);
 * var_dump($queries );
 * </code>
 * @package Database
 * @subpackage Advanced
 */
function db_query_log($q)
{
    static $index = array();
    if (is_bool($q)) {
        $index = array_unique($index);
        return $index;
    } else {

        $index[] = $q;

    }
}

/**
 * Updates multiple items in the database
 *
 *
 * @package Database
 * @subpackage Advanced
 * @param string $get_params Your parrams to be passed to the get() function
 * @param bool|string $save_params Array of the new data
 * @return array|bool|string
 * @see get()
 * @see save_data()
 * @example
 * <code>
 * //example updates the is_active flag of all content
 * mass_save("table=content&is_active=n", 'is_active=y');
 * </code>
 */
function mass_save($get_params, $save_params = false)
{
    return \mw\DbUtils::mass_save($get_params, $save_params);

}

function db_get($table, $criteria, $cache_group = false)
{
    return db_get_long($table, $criteria, $limit = false, $offset = false, $orderby = false, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);
}


/**
 * Gets data from a table
 *
 *
 * @param bool|string $table table name
 * @param array|bool $criteria
 * @param array|bool|int $limit
 * @param array|bool|int $offset
 * @param bool|string $orderby
 * @param bool|string $cache_group
 * @param bool|string $debug
 * @param bool|string|array $ids
 * @param bool $count_only
 * @param bool|array $only_those_fields
 * @param bool|array $exclude_ids
 * @param bool|string $force_cache_id
 * @param bool $get_only_whats_requested_without_additional_stuff
 * @return array
 * @since Version 0.320
 * @package Database
 * @subpackage Advanced
 * @see get
 */
function db_get_long($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false)
{

    return \mw\Db::get_long($table,  $criteria, $limit, $offset, $orderby , $cache_group, $debug , $ids, $count_only, $only_those_fields, $exclude_ids, $force_cache_id, $get_only_whats_requested_without_additional_stuff);

}


function db_get_tables_list()
{
    return \mw\DbUtils::get_tables();
}

function db_table_exist($table)
{
    return \mw\DbUtils::table_exist($table);

}

/**
 * Gets all field names from a DB table
 *
 * @param $table string
 *            - table name
 * @param $exclude_fields array
 *            - fields to exclude
 * @return array
 * @author Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */

function db_get_table_fields($table, $exclude_fields = false)
{
    return \mw\Db::get_fields($table,$exclude_fields);

}


function db_update_position($table, $data = array())
{
    return \mw\DbUtils::update_position_field($table,$data);

}





/**
 * Deletes item by id from db table
 *
 * @param string $table Your da table name
 * @param int|string $id The id to delete
 * @param string $field_name You can set custom column to delete by it, default is id
 *
 * @return bool
 * @example
 * <code>
 * //delete content with id 5
 *  db_delete_by_id('content', $id=5);
 * </code>
 *
 * @package Database
 */
function db_delete_by_id($table, $id = 0, $field_name = 'id')
{
    return \mw\Db::delete_by_id($table,$id,$field_name);

}

/**
 * Copy entire database row
 *
 * @param string $table Your table
 * @param int|string $id The id to copy
 * @param string $field_name You can set custom column to copy by it, default is id
 *
 *
 * @return bool|int
 * @example
 * <code>
 * //copy content with id 5
 *  db_copy_by_id('content', $id=5);
 * </code>
 *
 * @package Database
 * @subpackage Advanced
 *
 */
function db_copy_by_id($table, $id = 0, $field_name = 'id')
{

    return \mw\DbUtils::copy_row_by_id($table,$id,$field_name);

}


/**
 * Creates database table from array
 *
 * You can pass an array of database fields and this function will set up the same db table from it
 *
 * @example
 * <pre>
 * To create custom table use
 *
 *
 * $table_name = MW_TABLE_PREFIX . 'my_new_table'
 *
 * $fields_to_add = array();
 * $fields_to_add[] = array('updated_on', 'datetime default NULL');
 * $fields_to_add[] = array('created_by', 'int(11) default NULL');
 * $fields_to_add[] = array('content_type', 'TEXT default NULL');
 * $fields_to_add[] = array('url', 'longtext default NULL');
 * $fields_to_add[] = array('content_filename', 'TEXT default NULL');
 * $fields_to_add[] = array('title', 'longtext default NULL');
 * $fields_to_add[] = array('is_active', "char(1) default 'y'");
 * $fields_to_add[] = array('is_deleted', "char(1) default 'n'");
 *  set_db_table($table_name, $fields_to_add);
 * </pre>
 *
 * @desc refresh tables in DB
 * @access        public
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param        string $table_name to alter table
 * @param        array $fields_to_add to add new columns
 * @param        array $column_for_not_drop for not drop
 * @return bool|mixed
 */
function set_db_table($table_name, $fields_to_add, $column_for_not_drop = array())
{
    return \mw\DbUtils::build_table($table_name, $fields_to_add, $column_for_not_drop);

}

/**
 * Add new table index if not exists
 *
 * @example
 * <pre>
 * db_add_table_index('title', $table_name, array('title'));
 * </pre>
 *
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param string $aIndexName Index name
 * @param string $aTable Table name
 * @param string $aOnColumns Involved columns
 * @param bool $indexType
 */
function db_add_table_index($aIndexName, $aTable, $aOnColumns, $indexType = false)
{
    return \mw\DbUtils::add_table_index($aIndexName, $aTable, $aOnColumns, $indexType);


}

/**
 * Set table's engine
 *
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param string $aTable
 * @param string $aEngine
 */
function db_set_engine($aTable, $aEngine = 'MyISAM')
{
    return \mw\DbUtils::set_table_engine($aTable, $aEngine);

}

/**
 * Create foreign key if not exists
 *
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param string $aFKName Foreign key name
 * @param string $aTable Source table name
 * @param array $aColumns Source columns
 * @param string $aForeignTable Foreign table name
 * @param array $aForeignColumns Foreign columns
 * @param array $aOptions On update and on delete options
 */
function db_add_foreign_key($aFKName, $aTable, $aColumns, $aForeignTable, $aForeignColumns, $aOptions = array())
{
    return \mw\DbUtils::add_foreign_key($aFKName, $aTable, $aColumns, $aForeignTable, $aForeignColumns, $aOptions);


}