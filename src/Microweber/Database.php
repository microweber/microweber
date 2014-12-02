<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Microweber\Utils\Database as DbUtils;

use Illuminate\Support\Facades\User as DefaultUserProvider;

class Database
{


    public $use_cache = true;
    public $app = null;

    public $default_limit = 30;


    public $table_cache_ttl = 60;
    private $filter_keys = [];


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
    public function get($params)
    {

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
        $data = $query->get();

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


    private static $custom_filters = [];

    public static function custom_filter($name, $callback)
    {
        self::$custom_filters[$name] = $callback;
    }

    public function map_filters($query, &$params)
    {

        if (!isset($params['limit'])) {
            $params['limit'] = $this->default_limit;
        }

        foreach ($params as $filter => $value) {


            $compare_sign = false;
            $compare_value = false;

            if (is_string($value)) {
                if (stristr($value, '[lt]')) {
                    $compare_sign = '<';
                    $value = str_replace('[lt]', '', $value);
                } else if (stristr($value, '[lte]')) {
                    $compare_sign = '<=';
                    $value = str_replace('[lte]', '', $value);
                } else if (stristr($value, '[st]')) {
                    $compare_sign = '<';
                    $value = str_replace('[st]', '', $value);
                } else if (stristr($value, '[ste]')) {
                    $compare_sign = '<=';
                    $value = str_replace('[ste]', '', $value);
                } else if (stristr($value, '[gt]')) {
                    $compare_sign = '>';
                    $value = str_replace('[gt]', '', $value);
                } else if (stristr($value, '[gte]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[gte]', '', $value);
                } else if (stristr($value, '[mt]')) {
                    $compare_sign = '>';
                    $value = str_replace('[mt]', '', $value);
                } else if (stristr($value, '[md]')) {
                    $compare_sign = '>';
                    $value = str_replace('[md]', '', $value);
                } else if (stristr($value, '[mte]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[mte]', '', $value);
                } else if (stristr($value, '[mde]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[mde]', '', $value);
                } else if (stristr($value, '[neq]')) {
                    $compare_sign = '!=';
                    $value = str_replace('[neq]', '', $value);
                } else if (stristr($value, '[eq]')) {
                    $compare_sign = '=';
                    $value = str_replace('[eq]', '', $value);
                } else if (stristr($value, '[int]')) {
                    $value = str_replace('[int]', '', $value);
                    $value = intval($value);
                } else if (stristr($value, '[is]')) {
                    $compare_sign = '=';
                    $value = str_replace('[is]', '', $value);
                } else if (stristr($value, '[like]')) {
                    $compare_sign = 'LIKE';
                    $value = str_replace('[like]', '', $value);
                    $compare_value = '%' . $value . '%';
                } else if (stristr($value, '[not_like]')) {
                    $value = str_replace('[not_like]', '', $value);
                    $compare_sign = 'NOT LIKE';
                    $compare_value = '%' . $value . '%';
                } else if (stristr($value, '[is_not]')) {
                    $value = str_replace('[is_not]', '', $value);
                    $compare_sign = 'NOT LIKE';
                    $compare_value = '%' . $value . '%';
                }
            }

            switch ($filter) {
                case 'order_by':
                    $criteria = explode(',', $value);
                    foreach ($criteria as $c) {
                        $c = explode(' ', $c);
                        if (isset($c[1])) {
                            $query = $query->orderBy($c[0], $c[1]);
                        } else if (isset($c[0])) {
                            $query = $query->orderBy($c[0]);
                        }
                    }
                    unset($params[$filter]);
                    break;
                case 'limit':
                    $criteria = intval($value);
                    $query = $query->take($criteria);
                    unset($params[$filter]);
                    break;
                case 'current_page':
                    $criteria = intval($value);
                    $query = $query->skip($criteria);

                    unset($params[$filter]);
                    break;
                case 'ids':
                    $ids = $value;
                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    }


                    $query = $query->whereIn('id', $ids);
                    unset($params[$filter]);
                    break;
                case 'exclude_ids':
                    unset($params[$filter]);
                    $ids = $value;
                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    }
                    $query = $query->whereNotIn('id', $ids);
                    break;
                case 'id':
                    unset($params[$filter]);
                    $criteria = trim($value);
                    $query = $query->where('id', $criteria);
                    break;

                case 'no_cache':
                    $this->useCache = false;
                    break;


                default:
                    if ($compare_sign != false) {
                        unset($params[$filter]);
                        if ($compare_value != false) {
                            $query = $query->where($filter, $compare_sign, $compare_value);

                        } else {
                            $query = $query->where($filter, $compare_sign, $value);

                        }
                    }

                    break;


            }


        }


        foreach (self::$custom_filters as $name => $callback) {
            if (!isset($params[$name])) {
                continue;
            }
            call_user_func_array($callback, [$query, $params[$name]]);
        }
        return $query;
    }


    public function map_array_to_table($table, $array)
    {
        if (!is_array($array)) {
            return $array;
        }

        $r = $this->get_fields($table);
        $r = array_diff($r, $this->filter_keys);

        $r = array_intersect($r, array_keys($array));
        $r = array_flip($r);
        $r = array_intersect_key($array, $r);
        return $r;
    }

    public function map_values_to_query($query, &$params)
    {
        foreach ($params as $column => $value) {


            switch ($value) {
                case '[not_null]':
                    $query->whereNotNull($column);
                    unset($params[$column]);
                    break;

                case '[null]':
                    $query->whereNull($column);
                    unset($params[$column]);
                    break;
            }


        }


        return $query;
    }


    public function get_fields($table)
    {
        $value = Cache::remember('model.columns.' . $table, $this->table_cache_ttl, function () use ($table) {
            return DB::connection()->getSchemaBuilder()->getColumnListing($table);
        });
        return $value;

    }


}