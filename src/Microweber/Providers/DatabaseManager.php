<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Microweber\Providers\Database\Utils as DbUtils;
use Microweber\Traits\QueryFilter;
use Microweber\Traits\ExtendedSave;
use Illuminate\Support\Facades\User as DefaultUserProvider;

class DatabaseManager extends DbUtils
{


    public $use_cache = true;

    /** @var \Microweber\Application  */
    public $app;

    use QueryFilter; //trait with db functions
    use ExtendedSave; //trait to save extended data, such as attributes, categories and images

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
     * @param string $params ['table'] the table name ex. content
     * @param string $params ['debug'] if true print the sql
     * @param string $params ['cache_group'] sets the cache folder to use to cache the query result
     * @param string $params ['no_cache']  if true it will no cache the sql
     * @param string $params ['count']  if true it will return results count
     * @param string $params ['page_count']  if true it will return pages count
     * @param string|array $params ['limit']  if set it will limit the results
     *
     * @function get
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

        if ($params === null) {
            $params = $table;
        } else {
            if ($params != false) {
                $params = parse_params($params);
            } else {
                $params = array();
            }
            $params['table'] = $table;
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


        $use_connection = false;

        if (isset($params['connection_name']) and !isset($_REQUEST['connection_name'])) {
            $use_connection = $params['connection_name'];
            unset($params['connection_name']);
        }


        if ($use_connection == false) {
            $query = $this->table($table);
        } else {
            $query = DB::connection($use_connection)->table($table);
        }

        $orig_params = $params;
        $items_per_page = false;

        if (!isset($params['limit'])) {
            $params['limit'] = $this->default_limit;
        }
        if (isset($params['no_limit'])) {
            unset($params['limit']);

        }

        if (isset($orig_params['page_count'])) {
            $orig_params['count_paging'] = $orig_params['page_count'];
        }


        if (isset($orig_params['count_paging']) and ($orig_params['count_paging'])) {
            if (isset($params['limit'])) {
                $items_per_page = $params['limit'];
                unset($params['limit']);
            }
            if (isset($params['page'])) {
                unset($params['page']);
            }
            if (isset($params['paging_param'])) {
                unset($params['paging_param']);
            }

            if (isset($params['current_page'])) {
                unset($params['current_page']);
            }
            $orig_params['count'] = true;

        }

        if (isset($params['orderby'])) {
            $params['order_by'] = $params['orderby'];
            unset($params['orderby']);
        }

        if (isset($orig_params['count']) and ($orig_params['count']) and isset($params['order_by'])) {
            unset($params['order_by']);
        }

        if (isset($params['groupby'])) {
            $params['group_by'] = $params['groupby'];
            unset($params['groupby']);
        }

        if (isset($orig_params['no_cache']) and ($orig_params['no_cache'])) {
            $use_cache = false;
        } else {
            $use_cache = $this->use_cache;
        }
        $query = $this->map_filters($query, $params, $table);

        $params = $this->map_array_to_table($table, $params);

        $query = $this->map_values_to_query($query, $params);


        $ttl = $this->table_cache_ttl;

        if (!isset($params['no_limit'])) {
            $cache_key = $table . crc32(serialize($orig_params) . $this->default_limit);
        } else {
            $cache_key = $table . crc32(serialize($params));
        }


        if (is_array($params) and !empty($params)) {
            $query = $query->where($params);
        }

        if (isset($orig_params['count']) and ($orig_params['count'])) {
            if ($use_cache == false) {
                $query = $query->count();
            } else {
                $query = Cache::tags($table)->remember($cache_key, $ttl, function () use ($query) {
                    return $query->count();
                });

            }
            if ($items_per_page != false) {
                $query = intval(floor($query / $items_per_page));

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
        if (isset($orig_params['sum']) and ($orig_params['sum'])) {
            $column = $orig_params['sum'];
            $query = $query->sum($column);
            return $query;
        }


        if ($use_cache == false) {
            $data = $query->get();
        } else {
            $data = Cache::tags($table)->remember($cache_key, $ttl, function () use ($query) {
                return $query->get();
            });
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

            if (is_object($data[0]) and isset($data[0]->id)) {
                return (array)$data[0];
            }

            return $data[0];
        }
        return $data;
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
     * $table = $this->table_prefix.'content';
     * $data = array();
     * $data['id'] = 0; //if 0 will create new content
     * $data['title'] = 'new title';
     * $data['content'] = '<p>Something</p>';
     * $save = save($table, $data);
     * </code>
     * @package Database
     */
    public function save($table, $data = false, $data_to_save_options = false)
    {

        if (is_array($table) and isset($table['table'])) {
            $data = $table;
            $table = $table['table'];
            unset($data['table']);
        }
        if (is_string($data)) {
            $data = parse_params($data);
        }

        if (!is_array($data)) {
            return false;
        }

        $original_data = $data;

        $is_quick = isset($original_data['quick_save']);

        $skip_cache = isset($original_data['skip_cache']);

        if (!isset($params['skip_timestamps'])) {
            if (!isset($params['id']) or (isset($params['id']) and $params['id'] == 0)) {
                if (!isset($params['created_at'])) {
                    $params['created_at'] = date("Y-m-d H:i:s");
                }
            }
            if (!isset($params['updated_at'])) {
                $params['updated_at'] = date("Y-m-d H:i:s");
            }
        }


        if ($is_quick == false) {
            if (isset($data['updated_at']) == false) {
                $data['updated_at'] = date("Y-m-d H:i:s");
            }
        }

        if ($skip_cache == false and isset($data_to_save_options) and !empty($data_to_save_options)) {
            if (isset($data_to_save_options['delete_cache_groups']) and !empty($data_to_save_options['delete_cache_groups'])) {
                foreach ($data_to_save_options ['delete_cache_groups'] as $item) {
                    $this->app->cache_manager->delete($item);
                }
            }
        }

        $user_sid = $this->app->user_manager->session_id();
        $the_user_id = $this->app->user_manager->id();


        if (!isset($data['session_id']) and $user_sid) {
            $data['session_id'] = $user_sid;
        }
        if (!isset($data['id'])) {
            $data['id'] = 0;
        }
        if (isset($data['cf_temp'])) {
            $cf_temp = $data['cf_temp'];
        }
        $allow_html = false;
        $allow_scripts = false;
        if (isset($data['allow_html']) and (!isset($_REQUEST['allow_html']))) {
            $allow_html = $data['allow_html'];
        }
        if (isset($data['allow_scripts']) and (!isset($_REQUEST['allow_scripts']))) {
            $allow_scripts = $data['allow_scripts'];
        }

        if (isset($data['debug']) and $data['debug'] == true) {
            $dbg = 1;
            unset($data['debug']);
        } else {
            $dbg = false;
        }
        if ($dbg != false) {
            var_dump($data);
        }

        $data['user_ip'] = MW_USER_IP;
        if (isset($data['id']) == false or $data['id'] == 0) {
            $data['id'] = 0;
            $l = $this->last_id($table);
            $data['new_id'] = intval($l + 1);
            $original_data['new_id'] = $data['new_id'];
        }

        if (!isset($the_user_id)) {
            $the_user_id = 0;
        }
        if (intval($data['id']) == 0) {
            if (isset($data['created_at']) == false) {
                $data['created_at'] = date("Y-m-d H:i:s");
            }
            if ($the_user_id) {
                $data['created_by'] = $the_user_id;
            }
            if ($the_user_id) {
                $data['edited_by'] = $the_user_id;
            }
        } else {
            if ($the_user_id) {
                $data['edited_by'] = $the_user_id;
            }
        }

        if (isset($data['position'])) {
            $data['position'] = intval($data['position']);
        }


        $table_assoc_name = $this->assoc_table_name($table);

        $criteria_orig = $data;

        $criteria = $this->map_array_to_table($table, $data);


        if ($allow_html == false) {
            $criteria = $this->app->format->clean_html($criteria);
        } else {
            if ($allow_scripts == false) {
                $criteria = $this->clean_input($criteria);
            }

        }

        $table = $this->app->format->clean_html($table);

        $criteria = $this->app->url_manager->replace_site_url($criteria);

        if ($data_to_save_options['use_this_field_for_id'] != false) {

            $criteria['id'] = $criteria_orig[$data_to_save_options['use_this_field_for_id']];
        }

        if ($dbg != false) {
            var_dump($criteria);
        }

        if (!isset($criteria['id'])) {
            $criteria['id'] = 0;
        }
        $criteria['id'] = intval($criteria['id']);
        if (intval($criteria['id']) == 0) {
            unset($criteria['id']);
            $id_to_return = $this->table($table_assoc_name)->insert($criteria);
            $id_to_return = $this->last_id($table);
        } else {
            $id_to_return = $this->table($table_assoc_name)->where('id', $criteria['id'])->update($criteria);
            $id_to_return = $criteria['id'];
        }

        if ($id_to_return == false) {
            $id_to_return = $this->last_id($table);
        }
        $id_to_return = intval($id_to_return);

        $original_data['table'] = $table;
        $original_data['id'] = $id_to_return;
        $cache_group = $this->assoc_table_name($table);
        $this->app->cache_manager->delete($cache_group);

        if ($skip_cache == false) {
            $cache_group = $this->assoc_table_name($table);
            $this->app->cache_manager->delete($cache_group . '/global');
            $this->app->cache_manager->delete($cache_group . '/' . $id_to_return);
            if (isset($criteria['parent_id'])) {
                $this->app->cache_manager->delete($cache_group . '/' . intval($criteria['parent_id']));
            }
        }
        return $id_to_return;

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
     * $table_name = $this->table_prefix . 'content';
     * $id = $this->last_id($table_name);
     * </pre>
     *
     */
    public function last_id($table)
    {

       // DB::getPdo()->lastInsertId();


        $last_id = $this->table($table)->orderBy('id', 'DESC')->take(1)->first();
        if (isset($last_id->id)) {
            return $last_id->id;
        }
    }

    public function q($q, $silent = false)
    {
        if (!$silent) {
            return DB::statement($q);
        }

        try {
            return DB::statement($q);
        } catch (Exception $e) {
            return;
        } catch (QueryException $e) {
            return;
        } catch (\Illuminate\Database\QueryException $e) {
            return;
        }

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

        if ($id === 0) {
            return false;
        }

        if (is_array($id)) {
            foreach ($id as $remove) {
                $c_id = $this->table($table)->where($field_name, '=', $remove)->delete();
            }
        } else {
            $c_id = $this->table($table)->where($field_name, '=', $id)->delete();
        }

        Cache::tags($table)->flush();
        return $c_id;
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

        if ($field_name == 'id'  and $id == 0) {
            return false;
        }

        if ($field_name == false) {
            $field_name = "id";
        }
        if($field_name == 'id' or is_numeric($id)){
            $id = intval($id);
        }
        $params = array();
        $params[$field_name] = $id;
        $params['table'] = $table;
        $params['single'] = true;

        $data = $this->get($params);
        return $data;
    }


    public function table($table)
    {
        return DB::table($table);
    }
}