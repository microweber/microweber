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