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

namespace MicroweberPackages\Database;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Content;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\Database\Utils as DbUtils;
use MicroweberPackages\Database\Traits\QueryFilter;
use MicroweberPackages\Database\Traits\ExtendedSave;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Media\Models\Media;

use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use function Opis\Closure\serialize as serializeClosure;
use function Opis\Closure\unserialize as unserializeClosure;

class DatabaseManager extends DbUtils
{
    public $use_cache = true;
    public $use_model_cache = [];

    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    use QueryFilter; //trait with db functions

    use ExtendedSave; //trait to save extended data, such as attributes, categories and images

    public function __construct($app = null)
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
     * Get items from the database.
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
     *
     * @return mixed Array with data or false or integer if page_count is set
     *
     * @example
     * <code>
     * //get content
     *  $results = $this->get("table=content&is_active=1");
     * </code>
     * @example
     *  <code>
     *  //get users
     *  $results = $this->get("table=users&is_admin=0");
     * </code>
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



        $cache_key_closures = 'cache';
        if($params){
            foreach ($params as $k => $v) {
                if (is_object($v) && $v instanceof \Closure) {

                  //  $serialized = serializeClosure($v);
                  //  $cache_key_closures .= crc32($serialized);

                    $serialized = hashClosure($v);
                    $cache_key_closures .= $serialized;
                }
            }
        }
        if (!isset($params['no_limit'])) {
            $cache_key = $table . crc32(json_encode($params) .   $cache_key_closures);
        } else {
            $cache_key = $table . crc32(json_encode($params) . $cache_key_closures);
        }

        $_query_get_cache_key = $cache_key;
        if (!$this->_query_get_cache_is_disabled) {
            if (isset($this->_query_get_cache[$table][$_query_get_cache_key]) and $this->_query_get_cache[$table][$_query_get_cache_key]) {
                // experimental
                return $this->_query_get_cache[$table][$_query_get_cache_key];
            }
        }




        $enable_triggers = true;
        if (isset($params['enable_triggers'])) {
            $enable_triggers = $params['enable_triggers'];
        }

        if (isset($params['disable_triggers']) and $params['disable_triggers']) {
            $enable_triggers = false;
        }

        $use_connection = false;

        if (isset($params['connection_name']) and !isset($_REQUEST['connection_name'])) {
            $use_connection = $params['connection_name'];
            unset($params['connection_name']);
        }

        if ($use_connection == false) {
            $query = $this->table($table, $params);
        } else {
            $query = DB::connection($use_connection)->table($table);
        }

        $orig_params = $params;
        $items_per_page = false;


        $do_not_replace_site_url = false;
        if (isset($params['do_not_replace_site_url'])) {
            $do_not_replace_site_url = $params['do_not_replace_site_url'];
        }

        $limit = $this->default_limit;
        if (!isset($params['limit'])) {
            $limit = $params['limit'] = $this->default_limit;
        } else {
            $limit = $params['limit'];
        }

        if (isset($params['nolimit'])) {
            $params['no_limit'] = $params['nolimit'];
            unset($params['nolimit']);

        }
        if (isset($params['no_limit'])) {
            unset($params['limit']);
        }

        if (isset($orig_params['page_count'])) {
            $orig_params['count_paging'] = $orig_params['page_count'];
        }
        if (isset($params['limit']) and ($params['limit'] == 'nolimit' or $params['limit'] == 'no_limit')) {
            unset($params['limit']);
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
            $use_cache = $this->use_cache = false;
        } else {
            $use_cache = $this->use_cache = true;
        }
        $cache_from_model = false;
        if (isset($this->use_model_cache[$table]) and $this->use_model_cache[$table]) {
            $use_cache = false;
            $cache_from_model = true;
        }

        if (!isset($params['filter'])) {
            $query = $this->map_filters($query, $params, $table);
        }
        $params = $this->map_array_to_table($table, $params);
        $query = $this->map_values_to_query($query, $params);

        $ttl = $this->table_cache_ttl;
        if (!$query) {
            return;
        }

//        $cache_key_closures = 'cache';
//        foreach ($orig_params as $k => $v) {
//            if (is_object($v) && $v instanceof \Closure) {
//
//                $serialized = serializeClosure($v);
//                $cache_key_closures .= crc32($serialized);
//            }
//        }

        if (!isset($params['no_limit'])) {
            $cache_key = 'db_get_'.$table . crc32(json_encode($orig_params) . $limit . $this->default_limit . $cache_key_closures);
        } else {
            $cache_key = 'db_get_'.$table . crc32(json_encode($params) . $cache_key_closures);
        }

        if (is_array($params) and !empty($params)) {
            //$query = $query->where($params);
            foreach ($params as $k => $v) {
                $query = $query->where($table . '.' . $k, '=', $v);
            }
        }


        if (isset($orig_params['count']) and ($orig_params['count'])) {
            if ($use_cache == false and $cache_from_model == false) {
                $query = $query->count();
            } else {
                $query = Cache::tags($table)->remember($cache_key, $ttl, function () use ($query) {
                    $queryCount = $query->count();
                    return $queryCount;
                });
            }
            if ($items_per_page != false and is_numeric($query)) {
                // return the pages count
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
        if (isset($orig_params['sum']) and ($orig_params['sum'])) {
            $column = $orig_params['sum'];
             $query = $query->sum($column);

//            $query = Cache::tags($table)->remember($cache_key, $ttl, function () use ($query,$column) {
//                $queryCount = $query->sum($column);
//                return $queryCount;
//            });


            return $query;
        }

        if (isset($orig_params['fields']) and $orig_params['fields'] != false) {
            if (is_string($orig_params['fields'])) {
                $is_fields = explode(',', $orig_params['fields']);
            } else {
                $is_fields = $orig_params['fields'];
            }
            $is_fields_q = [];
            if ($is_fields) {
                foreach ($is_fields as $is_field) {
                    if (is_string($is_field)) {
                        $is_field = trim($is_field);
                        if ($is_field != '') {
                            $is_fields_q[] = $table . '.' . $is_field;
                        }
                    }
                }
            }
            if ($is_fields_q) {
                $query = $query->select($is_fields_q);
            }

        }

        if ($use_cache == false) {

            $data = $query->get();

            if (isset($orig_params['fields']) and $orig_params['fields'] != false) {
                if (method_exists($query, 'getModel')) {
                    $builderModel = $query->getModel();
                    $data->makeHidden(array_keys($builderModel->attributesToArray()));
                }
            }

            if (isset($orig_params['collection']) and ($orig_params['collection'])) {

            } else {

                $data = $data->toArray();
            }


        } else {

            $data = Cache::tags($table)->remember($cache_key, $ttl, function () use ($cache_key, $query, $orig_params) {

                $queryResponse = $query->get();

                if (isset($orig_params['fields']) and $orig_params['fields'] != false) {
                    if (method_exists($query, 'getModel')) {
                        $builderModel = $query->getModel();
                        $queryResponse->makeHidden(array_keys($builderModel->attributesToArray()));
                    }
                }
                if (isset($orig_params['collection']) and ($orig_params['collection'])) {
                    return $queryResponse;
                }
                return $queryResponse->toArray();

            });


        }

        if ($data == false or empty($data)) {
            return false;
        }

        if (is_object($data)
        ) {
            if (isset($orig_params['collection']) and ($orig_params['collection'])) {
                return $data;
            } else {
                $data = $this->_collection_to_array($data);
            }
        }



        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = (array)$v;
            }
        }


        if (empty($data)) {
            return false;
        } else {
            if (!$do_not_replace_site_url) {
                $data = $this->app->url_manager->replace_site_url_back($data);
            }
        }


        if (!is_array($data)) {
            $this->_query_get_cache[$table][$_query_get_cache_key] = $data;
            return $data;
        }

        if ($enable_triggers) {
            $data = $this->app->event_manager->response('mw.database.' . $table . '.get', $data);
        }

        if (isset($orig_params['single']) || isset($orig_params['one'])) {
            if (!isset($data[0])) {
                return false;
            }

            if (is_object($data[0]) and isset($data[0]->id)) {
                // might be a bug here?
                $this->_query_get_cache[$table][$_query_get_cache_key] = (array)$data[0];
                return (array)$data[0];
            }
            $this->_query_get_cache[$table][$_query_get_cache_key] = $data[0];
            return $data[0];
        }
        $this->_query_get_cache[$table][$_query_get_cache_key] = $data;

        return $data;
    }

    /**
     * Generic save data function, it saves data to the database.
     *
     * @param $table
     * @param $data
     * @param bool $data_to_save_options
     *
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
        $this->clearCache();
        $original_data = $data;

        $is_quick = isset($original_data['quick_save']);

        $skip_cache = isset($original_data['skip_cache']);

        /*if (!isset($params['skip_timestamps'])) {
            if (!isset($params['id']) or (isset($params['id']) and $params['id'] == 0)) {
                if (!isset($params['created_at'])) {
                    $params['created_at'] = date('Y-m-d H:i:s');
                }
            }
            if (!isset($params['updated_at'])) {
                $params['updated_at'] = date('Y-m-d H:i:s');
            }
        }*/


        if (isset($data['updated_at'])) {
            try {
                $carbonUpdatedAt = Carbon::parse($data['updated_at']);
                $data['updated_at'] = $carbonUpdatedAt->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
            }
        }


        if ($is_quick == false) {
            if (isset($data['updated_at']) == false) {
                $data['updated_at'] = date('Y-m-d H:i:s');
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

        /* if (isset($data['debug']) and $data['debug'] == true) {
             $dbg = 1;
             unset($data['debug']);
         } else {
             $dbg = false;
         }
         if ($dbg != false) {
             var_dump($data);
         }*/

        if (!isset($data['user_ip'])) {
            $data['user_ip'] = user_ip();
        }
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

            if (isset($data['created_at'])) {
                try {
                    $carbonUpdatedAt = Carbon::parse($data['created_at']);
                    $data['created_at'] = $carbonUpdatedAt->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                }
            } else {
                $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            }


//            if (isset($data['created_at']) == false) {
//                $data['created_at'] = date('Y-m-d H:i:s');
//            }
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

               // $evil = ['(?<!\w)on\w*', 'xmlns', 'formaction', 'xlink:href', 'FSCommand', 'seekSegmentTime'];
              //  $clearInput = new HTMLClean();
               // $criteria = $clearInput->cleanArray($criteria);

            }

        }
        $criteria = $this->app->url_manager->replace_site_url($criteria);

        if (is_array($data_to_save_options) and $data_to_save_options['use_this_field_for_id'] != false) {
            $criteria['id'] = $criteria_orig[$data_to_save_options['use_this_field_for_id']];
        }


        if (!isset($criteria['id'])) {
            $criteria['id'] = 0;
        }
        $criteria['id'] = intval($criteria['id']);

        $criteria = $criteria_overwrite = $this->app->event_manager->response('mw.database.' . $table . '.save.params', $criteria);
        $criteria = $this->map_array_to_table($table, $criteria);

        if(!$criteria){
            return;
        }

//        $auto_fields = ['created_by','edited_by','created_at','updated_at','created_by','session_id','id'];


        if (intval($criteria['id']) == 0) {
            unset($criteria['id']);
            $engine = $this->get_sql_engine();
            if ($engine == 'pgsql') {
                $highestId = $this->table($table)->select(DB::raw('MAX(id)'))->first();
                $next_id = 0;
                if (!isset($highestId->max)) {
                    $next_id = 1;
                } else {
                    $next_id = $highestId->max + 1;
                }
                if (!empty($criteria)) {
                    $criteria['id'] = $next_id;
                }
            }
            $id_to_return = $this->table($table_assoc_name)->insert($criteria);
            $id_to_return = $this->last_id($table);

        } else {
            $insert_or_update = $highestId = $this->table($table)->where('id', $criteria['id'])->count();
            if ($insert_or_update != 0) {
                $insert_or_update = 'update';
            } else {
                $insert_or_update = 'insert';
            }
            $id_to_return = $this->table($table_assoc_name)->where('id', $criteria['id'])->$insert_or_update($criteria);
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
            $this->app->cache_manager->delete($cache_group . '');
            $this->app->cache_manager->delete('content/global/full_page_cache');
            $this->app->cache_manager->delete($cache_group . '/' . $id_to_return);
            if (isset($criteria['parent_id'])) {
                $this->app->cache_manager->delete($cache_group . '/' . intval($criteria['parent_id']));
            }
        }

        $criteria_overwrite['id'] = $id_to_return;
        $this->app->event_manager->trigger('mw.database.' . $table . '.save.after', $criteria_overwrite);

        return $id_to_return;
    }

    /**
     * Get last id from a table.
     *
     * @desc Get last inserted id from a table, you must have 'id' column in it.
     *
     * @param $table
     *
     * @return bool|int
     *
     * @example
     * <pre>
     * $table_name = $this->table_prefix . 'content';
     * $id = $this->last_id($table_name);
     * </pre>
     */
    public function last_id($table)
    {

        // DB::getPdo()->lastInsertId();

        $last_id = $this->table($table)->select(['id'])->orderBy('id', 'DESC')->take(1)->first();
        if (isset($last_id->id)) {
            return $last_id->id;
        }
    }

    public function q($q, $silent = false)
    {
        if (!$silent) {
            $q = DB::statement($q);
            $q = $this->_collection_to_array($q);

            return $q;
        }

        try {
            $q = DB::statement($q);
            $q = $this->_collection_to_array($q);

            return $q;
        } catch (\Exception $e) {
            return;
        } catch (\Illuminate\Database\QueryException $e) {
            return;
        } catch (\QueryException $e) {
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
     * @function $this->query
     * @desc Executes plain query in the database.
     *
     * @param string $q Your SQL query
     * @param string|bool $cache_id It will save the query result in the cache. Set to false to disable
     * @param string|bool $cache_group Stores the result in certain cache group. Set to false to disable
     * @param bool $only_query If set to true, will perform only a query without returning a result
     * @param array|bool $connection_settings
     *
     * @return array|bool|mixed
     *
     * @example
     *  <code>
     *  //make plain query to the db
     * $table = $this->table_prefix.'content';
     *    $sql = "SELECT id FROM $table WHERE id=1   ORDER BY updated_at DESC LIMIT 0,1 ";
     *  $q = $this->query($sql, $cache_id=crc32($sql),$cache_group= 'content');
     *
     * </code>
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
        $q = $this->_collection_to_array($q);
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
     * Deletes item by id from db table.
     *
     * @param string $table Your da table name
     * @param int|string $id The id to delete
     * @param string $field_name You can set custom column to delete by it, default is id
     *
     * @return bool
     *
     * @example
     * <code>
     * //delete content with id 5
     *  $this->delete_by_id('content', $id=5);
     * </code>
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
        $this->app->cache_manager->delete('content/global/full_page_cache');
        $this->clearCache();

        return $c_id;
    }

    /**
     * Get table row by id.
     *
     * It returns full db row from a db table
     *
     * @param string $table Your table
     * @param int|string $id The id to get
     * @param string $field_name You can set custom column to get by it, default is id
     *
     * @return array|bool|mixed
     *
     * @example
     * <code>
     * //get content with id 5
     * $cont = $this->get_by_id('content', $id=5);
     * </code>
     */
    public function get_by_id($table, $id = 0, $field_name = 'id')
    {
        if (!$id) {
            return;
        }
        if ($field_name == 'id' and $id == 0) {
            return false;
        }

        if ($field_name == false) {
            $field_name = 'id';
        }
        if ($field_name == 'id' or is_numeric($id)) {
            $id = intval($id);
        }
        $params = array();
        $params[$field_name] = $id;
        $params['table'] = $table;
        $params['single'] = true;

        $data = $this->get($params);

        return $data;
    }


    public $_query_get_cache = [];
    public $_query_get_cache_is_disabled = false;
    public function clearCache($table = false){


        $this->_query_get_cache = []; //empty whole local cache
        $this->_query_get_cache_is_disabled = true; //disable the cache after flush

       AbstractRepository::disableCache();


//        if($table){
//            $this->_query_get_cache[$table] = null;
//        } else {
//        // $this->_query_get_cache[$table] = [];
//        }
    }


    static $model_cache_mem = [];



    public function table($table, $params = [])
    {
     // return DB::table($table);

//        if (isset(self::$model_cache_mem[$table])) {
//           // $instance = self::$model_cache_mem[$table]->newInstance($params, true);
//         //   $instance = self::$model_cache_mem[$table]->newModelQuery($params, true);
//          //  dump($instance);
//         //   return self::$model_cache_mem[$table];
//          //  return $instance;
//        }

        $this->use_model_cache[$table] = false;
        //@todo move this to external resolver class or array
        if ($table == 'content' || $table == 'categories' || $table == 'options') {

            $this->use_model_cache[$table]= true;

            if ($table == 'content') {
                $model = new Content($params);
                 // $model = app()->make(Content::class);

                //    $model::boot();
            } else if ($table == 'categories') {
                 $model = new Category($params);


            }else if ($table == 'options') {
                 $model = new Option($params);


            }
          //  self::$model_cache_mem[$table] = $model ->newInstance($params, true);;


            if ($params and isset($params['filter']) and method_exists($model, 'modelFilter')) {
                $filterParams = $params;
                if (!empty($params['filter'])) {
                    if (is_string($params['filter'])) {
                        $params['filter'] = html_entity_decode($params['filter'], null, 'UTF-8');
                        $params['filter'] = urldecode($params['filter']);
                        $filterParams = parse_params($params['filter']);

                    } else if (is_array($params['filter'])) {
                        $filterParams = $params['filter'];
                    }
                }

                if ($filterParams) {
                    return $model->filter($filterParams);
                } else {
                    return $model->query();

                }
            } else {
                return $model->query();
            }
        }

        if ($table == 'custom_fields') {
            $this->use_model_cache[$table] = true;
            return CustomField::query();
        }

        if ($table == 'custom_fields_values') {
            $this->use_model_cache[$table] = true;
            return CustomFieldValue::query();
        }

        if ($table == 'media') {
            $this->use_model_cache[$table]= true;
            return Media::query();
        }



        return DB::table($table);
    }

    public function supports($table, $feature)
    {
        if(is_object($table)){
            $model = $table;
        } else {
            $model = $this->table($table);
        }
        $methodVariable = array($model, $feature);
        if (is_callable($methodVariable, true, $callable_name)) {
            return true;
        }
    }

    private function _collection_to_array($data)
    {
        return collection_to_array($data);
    }
}
