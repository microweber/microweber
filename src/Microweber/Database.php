<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Microweber\Utils\Database as DbUtils;
use Microweber\Traits\QueryFilter;

use Illuminate\Support\Facades\User as DefaultUserProvider;

class Database
{

    use QueryFilter; //trait with db functions

    public $use_cache = true;
    public $app = null;


    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
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
    public function get($table_name_or_params,$params=null)
    {
        if($params === null){
            $params = $table_name_or_params;
        } else {
            if($params != false){
                $params = parse_params($params);
            } else {
                $params = array();
            }
            $params['table'] = $table_name_or_params;
        }

        if (is_string($params)) {
            $params = parse_params($params);
        }

        if (!isset($params['table'])) {
            return false;
        } else {
            $table = trim($params['table']);
            unset($params['table']);
        }
        if (!$table) {
            return false;
        }




        $query = DB::table($table);


        $orig_params = $params;
        $items_per_page = false;
        if (isset($orig_params['count_paging']) and ($orig_params['count_paging'])) {
            if (isset($params['limit'])) {
                $items_per_page = $params['limit'];
                unset($params['limit']);
            }
            if (isset($params['page'])) {
                unset($params['page']);
            }
            $orig_params['count'] = true;
        }


        $query = $this->map_filters($query, $params);
        $params = $this->map_array_to_table($table, $params);
        $query = $this->map_values_to_query($query, $params);


        if (is_array($params) and !empty($params)) {
            $query = $query->where($params);
        }

        if (isset($orig_params['count']) and ($orig_params['count'])) {
            $query = $query->count();
            if ($items_per_page != false) {
                $query = intval(ceil($query / $items_per_page));
            }
            return $query;
        }
        if (isset($orig_params['min']) and ($orig_params['min'])) {
            $column = $orig_params['min'];
            $query = $query->min($column);
            return $query;
        }
        if (isset($orig_params['max']) and ($orig_params['max'])) {
            $column = $orig_params['max'];
            $query = $query->max($column);
            return $query;
        }
        if (isset($orig_params['avg']) and ($orig_params['avg'])) {
            $column = $orig_params['avg'];
            $query = $query->avg($column);
            return $query;
        }

        if(!$this->use_cache){
            $data = $query->get();

        } else {
            $ttl = $this->table_cache_ttl;
            $cache_key = $table.crc32(serialize($orig_params));
            $data =  Cache::tags($table)->remember($cache_key, $ttl, function() use ($query)
            {
               return $query->get();
                // return false;
            });

            //$data =  $query->cacheTags($table)->remember($ttl,$cache_key)->get();
        }


        if ($data == false or empty($data)) {
            return false;
        }
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = (array)$v;
            }
        }
        if (empty($data)) {
            return false;
        } else {
            $data = $this->app->url_manager->replace_site_url_back($data);
        }


        if (!is_array($data)) {
            return $data;
        }

        if (isset($orig_params['single']) || isset($orig_params['one'])) {
            if (!isset($data[0])) {
                return false;
            }
            return $data[0];
        }
        return $data;
    }


    public function save($table_name_or_params,$params=null)
    {

        if($params === null){
            $params = $table_name_or_params;
        } else {
            if($params != false){
                $params = parse_params($params);
            } else {
                $params = array();
            }
            $params['table'] = $table_name_or_params;
        }


        if (!isset($params['table'])) {
            return false;
        } else {
            $table = trim($params['table']);
            unset($params['table']);
        }
        if (!$table) {
            return false;
        }

        $query = DB::table($table);


        if (is_string($params)) {
            $params = parse_params($params);
        }
        if (!isset($params['created_on']) == false) {
            $params['created_on'] = date("Y-m-d H:i:s");
        }
        if (!isset($params['updated_on']) == false) {
            $params['updated_on'] = date("Y-m-d H:i:s");
        }


        if (!isset($params['id'])) {
            $params['id'] = 0;
        }

        $data['session_id'] = mw()->user_manager->session_id();


        $params = $this->map_array_to_table($table, $params);

        $id_to_return = false;
        $params = $this->app->url_manager->replace_site_url($params);

        $params['id'] = intval($params['id']);
        if (intval($params['id']) == 0) {
            $id_to_return = $query->insert($params);
            $id_to_return = DB::getPdo()->lastInsertId();
        } else {
            unset($params['created_on']);
            $id_to_return = $query->where('id', $params['id'])->update($params);
            $id_to_return = $params['id'];
        }

        Cache::tags($table)->flush();
        return ($id_to_return);


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

//        $query = DB::table($table);
//
//        if(!$this->use_cache){
//            $data = $query->where($field_name, '=', $id)->first();
//
//        } else {
//            $ttl = $this->table_cache_ttl;
//$cache_key = $table.crc32($field_name.$id);
//
//            $data =  $query->cacheTags($table)->remember($ttl,$cache_key)->where($field_name, '=', $id)->first();
//        }
//
//
//        $data = (array) $data;
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
        $c_id = DB::table($table)->where($field_name, '=', $id)->delete();
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
     *    $sql = "SELECT id FROM $table WHERE id=1   ORDER BY updated_on DESC LIMIT 0,1 ";
     *  $q = $this->query($sql, $cache_id=crc32($sql),$cache_group= 'content/global');
     *
     * </code>
     *
     *
     *
     */
    public function query($q, $cache_id = false, $cache_group = 'global', $only_query = false, $connection_settings = false)
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

    public function q($q)
    {
        return DB::select($q);
    }



}