<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Microweber\Utils\Database as DbUtils;
use Microweber\Traits\QueryFilter;
use Microweber\Traits\ExtendedSave;


class Database
{

    use QueryFilter; //trait with db functions

    use ExtendedSave; //trait to save extended data, such as attributes, categories and images

    public $use_cache = true;
    public $app = null;
    public $default_limit = 30;

    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $get_default_limit_from_options = $this->app->option_manager->get_items_per_page();
        if ($get_default_limit_from_options != false) {

            $this->default_limit = intval($get_default_limit_from_options);
        }

    }


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
     *
     *|-----------------------------------------------------------------------------
     *| Parameter        | Description      | Values
     *|------------------------------------------------------------------------------
     *| table            | the name of the db table, without prefix | ex. users, content, categories,etc
     *| debug            | prints debug information  | true or false
     *| order_by        | you can order by any field in your table  | ex. get("table=content&orderby=id desc")
     *| single            | if set returns only the 1st result |
     *| count            | if set returns results count |  ex. get("table=content&count=true")
     *| limit            | limit the results |  ex. get("table=content&limit=5")
     *| current_page    | get the current page by limit offset |  ex. get("table=content&limit=5&curent_page=2")
     *
     * @function get
     * @param $table_name_or_params
     * @param $params
     * @return mixed Array with data or false or integer if page_count is set
     *
     *
     *
     * @example
     * <code>
     * //get content
     *  $results = $this->get("table=content&is_active=1");
     * </code>
     *
     * @example
     *  <code>
     *  //get users
     *  $results = $this->get("table=users&is_admin=0");
     * </code>
     *
     * @package Database
     */
    public function get($table, $params = null)
    {
        return $this->app->database_manager->get($table, $params);
    }


    public function save($table, $params = null)
    {
        return $this->app->database_manager->save($table, $params);
    }


    public function table($table)
    {

        $query = DB::table($table);
        return $query;
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
     * $cont = $this->get_by_id('content', $id=5);
     * </code>
     *
     * @package Database
     * @subpackage Advanced
     */
    public function get_by_id($table, $id = 0, $field_name = 'id')
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }
        if ($field_name == false) {
            $field_name = "id";
        }

        $params = array();
        $params[$field_name] = $id;
        $params['table'] = $table;
        $params['single'] = true;

        $data = $this->get($params);

        return $data;
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
     *  $this->delete_by_id('content', $id=5);
     * </code>
     *
     * @package Database
     */
    public function delete_by_id($table, $id = 0, $field_name = 'id')
    {

        if ($id == 0) {
            return false;
        }
        if (is_array($id)) {
            foreach ($id as $remove) {
                $c_id = DB::table($table)->where($field_name, '=', $remove)->delete();

            }
        } else {
            $c_id = DB::table($table)->where($field_name, '=', $id)->delete();

        }


        Cache::tags($table)->flush();
        return $c_id;
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
     * @function $this->query
     * @desc Executes plain query in the database.
     *
     * @param string $q Your SQL query
     * @param string|bool $cache_id It will save the query result in the cache. Set to false to disable
     * @param string|bool $cache_group Stores the result in certain cache group. Set to false to disable
     * @param bool $only_query If set to true, will perform only a query without returning a result
     * @param array|bool $connection_settings
     * @return array|bool|mixed
     *
     * @example
     *  <code>
     *  //make plain query to the db
     * $table = $this->table_prefix.'content';
     *    $sql = "SELECT id FROM $table WHERE id=1   ORDER BY updated_at DESC LIMIT 0,1 ";
     *  $q = $this->query($sql, $cache_id=crc32($sql),$cache_group= 'content/global');
     *
     * </code>
     *
     *
     *
     */
    public function query($q, $cache_id = false, $cache_group = 'global', $only_query = false)
    {
        if (trim($q) == '') {
            return false;
        }


        $error['error'] = array();
        $results = false;

        if ($cache_id != false and $cache_group != false) {

            $cache_id = $cache_id . crc32($q);
            $results = $this->app->cache_manager->get($cache_id, $cache_group);
            if ($results != false) {
                if ($results == '---empty---' or (is_array($results) and empty($results))) {
                    return false;
                } else {
                    return $results;
                }
            }
        }


        $q = DB::select($q);


        if ($only_query != false) {
            return true;
        }
        $q = (array)$q;
        if (isset($q[0])) {
            foreach ($q as $k => $v) {
                $q[$k] = (array)$v;
            }
        }


        if ($only_query == false and empty($q) or $q == false and $cache_group != false) {
            if ($cache_id != false) {

                $this->app->cache_manager->save('---empty---', $cache_id, $cache_group);
            }
            return false;
        }
        if ($only_query == false) {
            if ($cache_id != false and $cache_group != false) {
                if (is_array($q) and !empty($q)) {
                    $this->app->cache_manager->save($q, $cache_id, $cache_group);
                } else {
                    $this->app->cache_manager->save('---empty---', $cache_id, $cache_group);
                }
            }
        }
        if ($cache_id != false) {
            $this->app->cache_manager->save($q, $cache_id, $cache_group);
        }
        return $q;
    }

    public function q($q, $silent = false)
    {
        if (!$silent) {
            return DB::statement($q);
        }

        try {
            $q = DB::statement($q);
        } catch (Exception $e) {
            return;
        }

        return $q;
    }


}