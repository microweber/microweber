<?php


/**
 * Microweber Idiorm Adapter
 *
 * Uses Idiorm with MW;
 *
 * @since 0.94
 * @uses Microweber/Application
 * @author (of Idiorm) Jamie Matthews
 * @collaborator  (of Idiorm)  Simon Holywell https://github.com/treffynnon
 * @link https://github.com/j4mie/idiorm
 *
 */


namespace Microweber\Adapters\Orm;
use Microweber\Application;

use ORM;

class IdiOrm
{
    public $default_limit = 30;
    public $app;
    public $filters;

    public function __construct($app = null)
    {

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = Application::getInstance();
            }
        }
        $con = $this->app->config('db');
        $table_prefix = $this->app->config->get('database.connections.mysql.prefix');
        $host = false;
        $username = false;
        $password = false;
        $dbname = false;
        if (isset($con['host'])) {
            $host = $con['host'];
        }

        $host_port = explode(':',$host);


        if (isset($con['user'])) {
            $username = $con['user'];
        }
        if (isset($con['pass'])) {
            $password = $con['pass'];
        }
        if (isset($con['dbname'])) {
            $dbname = $con['dbname'];
        }

        if(isset($host_port[1])){
            $port = intval($host_port[1]);
            $host = trim($host_port[0]);
            ORM::configure('mysql:host=' . $host . ';port=' . $port. ';dbname=' . $dbname);
        } else {
            ORM::configure('mysql:host=' . $host . ';dbname=' . $dbname);

        }

        ORM::configure('username', $username);
        ORM::configure('password', $password);
        ORM::configure('caching', true);
        ORM::configure('logging', true);
        ORM::configure('caching_auto_clear', true);
        //ORM::configure('return_result_sets', true); // returns result sets
        ORM::configure('logger', function ($log_string, $query_time) {
            mw('db')->query_log($log_string);
        });
        ORM::configure('cache_query_result', function ($cache_key, $value, $table_name, $connection_name) use ($app) {
            $cache_group = $app->db->guess_cache_group($table_name);
            if (empty($value) or $value === false) {
                $value = '--empty--';
            }

            return $app->cache->save($value, $cache_key, $cache_group);
        });
        ORM::configure('check_query_cache', function ($cache_key, $table_name, $connection_name) use ($app) {

            $cache_group = $app->db->guess_cache_group($table_name);
            $cached = $app->cache->get($cache_key, $cache_group);
            if (is_string($cached) and $cached == '--empty--') {
                return array();
            }
            if ($cached !== false) {
                return $cached;
            } else {
                return false;
            }
        });
        ORM::configure('clear_cache', function ($table_name, $connection_name) use ($app) {
            $cache_group = $app->db->guess_cache_group($table_name);
            return $app->cache->delete($cache_group);
        });

        ORM::configure('create_cache_key', function ($query, $parameters, $table_name, $connection_name) use ($app) {
            $is_int = false;

            $query_hash = $query . join(',', $parameters);
            $cache_group = $app->db->guess_cache_group($table_name);
            if ($is_int == false) {
                $cache_group = 'global';
            } else {
                $cache_group = '' . $is_int;
            }
            $my_key = $cache_group . '/orm-' . (crc32($query_hash));
            return $my_key;
        });

        if (defined("MW_IS_INSTALLED") and MW_IS_INSTALLED == true) {
            $limit = $this->app->option->get('items_per_page ', 'website');
            if ($limit != false) {
                $this->default_limit = $limit;
            }

        }


    }

    function filter($key, $callback)
    {
        return $this->filters[$key] = $callback;
    }

    function configure($key, $val = false, $connection_name = 'default')
    {
        return ORM::configure($key, $val, $connection_name);
    }

    function one($table, $params = false)
    {
        return $this->get($table, $params, 'findOne');
    }

    function get($table, $params = false, $get_method = false, $return_method = false)
    {


        $table_real = $this->app->database->real_table_name($table);
        event_trigger('orm_get', $table);

        $orm = ORM::for_table($table_real)->table_alias($table);
        if (is_string($params)) {
            parse_str($params, $params2);
            $params = $params2;
        }
        $group_by = false;
        $order_by = false;
        $count = false;
        $no_limit = false;
        $limit = $this->default_limit;
        if ($limit == false) {
            $limit = 30;
        }
        $offset = false;
        $min = false;
        $max = false;
        $avg = false;
        $ids = false;
        $exclude_ids = false;
        $current_page = false;
        $count_paging = false;
        $to_search_in_fields = false;
        $to_search_keyword = false;
        $fields = false;
        $filter = false;
        $filter_params = $params;

        if (is_array($params)) {

            if (isset($params['filter'])) {
                $filter = $params['filter'];
                unset($params['filter']);
            }

            if ($filter != false and is_array($this->filters) and !empty($this->filters)) {
                $filters = array_merge($this->filters, $filter);
                $filter = $filters;
            } else {
                $filter = $this->filters;
            }


            if (is_array($filter)) {
                foreach ($filter as $k => $v) {
                    if (isset($params[$k]) and is_callable($v)) {
                        call_user_func($v, $orm, $params[$k], $params);
                    }
                }
            }


            if (isset($params['group_by'])) {
                $group_by = $params['group_by'];
                unset($params['group_by']);
            }
            if (isset($params['ids'])) {
                $ids = $params['ids'];
                unset($params['ids']);
            }
            if (isset($params['exclude_ids'])) {
                $exclude_ids = $params['exclude_ids'];
                unset($params['exclude_ids']);
            }

            if (isset($params['fields'])) {
                $fields = $params['fields'];
                unset($params['fields']);
            }
            if (isset($params['order_by'])) {
                $order_by = $params['order_by'];
                unset($params['order_by']);
            }
            if (isset($params['orderby'])) {
                $order_by = $params['orderby'];
                unset($params['orderby']);
            }
            if (isset($params['count'])) {
                $count = $params['count'];
                unset($params['count']);
            }
            if (isset($params['limit']) and $params['limit'] != false) {
                $limit = $params['limit'];

                unset($params['limit']);
            }
            if (isset($params['no_limit']) and $params['no_limit'] != false) {
                $no_limit = $params['no_limit'];

                unset($params['limit']);
            }
            if (isset($params['offset'])) {
                $offset = $params['offset'];
                unset($params['offset']);
            }
            if (isset($params['min'])) {
                $min = $params['min'];
                unset($params['min']);
            }
            if (isset($params['max'])) {
                $max = $params['max'];
                unset($params['max']);
            }
            if (isset($params['avg'])) {
                $avg = $params['avg'];
                unset($params['avg']);
            }

            if (isset($params['current_page'])) {
                $current_page = $params['current_page'];
                unset($params['current_page']);
            }

            if (isset($params['paging_param'])) {
                $paging_param = $params['paging_param'];
                if (isset($params[$paging_param])) {
                    $current_page = $params[$paging_param];
                    unset($params[$paging_param]);
                }
                unset($params['paging_param']);
            }
            if (isset($params['page'])) {
                $current_page = $params['page'];
                unset($params['page']);
            }
            if (isset($params['search_in_fields'])) {
                $to_search_in_fields = $params['search_in_fields'];
            }
            if (isset($params['keyword'])) {
                $to_search_keyword = $params['keyword'];
            }

            if (isset($params['count_paging'])) {
                $count = true;
                $count_paging = true;
                unset($params['count_paging']);
            }
            if (isset($params['page_count'])) {
                $count = true;
                $count_paging = true;
                unset($params['page_count']);
            }


        }

        $params_to_fields = $this->app->database->map_array_to_table($table, $params);

        if (is_array($params) and !empty($params)) {
            if ($fields != false and is_string($fields)) {
                $fields = explode(',', $fields);
            }
            if (is_array($fields) and !empty($fields)) {
                foreach ($fields as $field) {
                    $field = trim($field);
                    if ($field != '') {
                        $orm->select($table . '.' . $field, $field);
                    }
                }
            }

            $joined_tables = array();
            $has_joined = false;
            foreach ($params as $k => $v) {
                if ($k == 'id') {
                    $orm->where_id_is($v);
                } else {
                    $joins = explode('.', $k);
                    if (isset($joins[1])) {
                        $table_alias = $joins[0];
                        $table_real = $this->app->database->real_table_name($table_alias);
                    }
                    if (isset($joins[1]) and !in_array($joins[0], $joined_tables) and $joins[0] != $table) {
                        $joined_tables[] = $table_alias;
                        $table_assoc = $this->app->database->assoc_table_name($table);

                        $has_joined = true;
                        $orm->select($table . '.*');


                        $orm->where_equal($table_alias . '.rel', $table_assoc);
                        $orm->left_outer_join($table_real, array($table_alias . '.rel_id', '=', $table . '.id'), $table_alias);
                        //$orm->left_outer_join($table_real, array($table_alias . '.'.$joins[0], '=', $table . '.'.$joins[1]), $table_alias);
                        //$orm->left_outer_join($table_real, array($table_alias . '.rel_id', '=', $table . '.id'), $table_alias);
                    }

                    if (!isset($joins[1])) {
                        if (isset($params_to_fields[$k])) {
                            $field_name = $k;
                            $field_value = $v;
                            $table_alias = $table;
                        } else {

                            $field_name = false;
                            $field_value = false;
                        }
                    } else if (isset($joins[1])) {
                        $field_name = $joins[1];
                        $field_value = $v;

                    }
                    if (isset($params_to_fields[$k])) {
                        if (is_int($v)) {
                            $v = strval($v);
                        }
                        $field_name = $k;
                        $field_value = $v;
                        $table_alias = $table;
                    }


                    $where_method = false;
                    if ($field_value !== false and $field_name) {

                        if (is_string($field_value) or is_int($field_value)) {

                            $field_value = trim($field_value);
                            $field_value_len = strlen($field_value);

                            $two_chars = substr($field_value, 0, 2);
                            $one_char = substr($field_value, 0, 1);
                            $compare_sign = false;

                            if ($field_value_len > 0) {
                                if (is_string($field_value)) {


                                    if (stristr($field_value, '[lt]')) {
                                        $one_char = '<';
                                        $field_value = str_replace('[lt]', '', $field_value);
                                    } else if (stristr($field_value, '[lte]')) {
                                        $two_chars = '<=';
                                        $field_value = str_replace('[lte]', '', $field_value);
                                    } else if (stristr($field_value, '[st]')) {
                                        $one_char = '<';
                                        $field_value = str_replace('[st]', '', $field_value);
                                    } else if (stristr($field_value, '[ste]')) {
                                        $two_chars = '<=';
                                        $field_value = str_replace('[ste]', '', $field_value);
                                    } else if (stristr($field_value, '[gt]')) {
                                        $one_char = '>';
                                        $field_value = str_replace('[gt]', '', $field_value);
                                    } else if (stristr($field_value, '[gte]')) {
                                        $two_chars = '>=';
                                        $field_value = str_replace('[gte]', '', $field_value);
                                    } else if (stristr($field_value, '[mt]')) {
                                        $one_char = '>';
                                        $field_value = str_replace('[mt]', '', $field_value);
                                    } else if (stristr($field_value, '[md]')) {
                                        $one_char = '>';
                                        $field_value = str_replace('[md]', '', $field_value);
                                    } else if (stristr($field_value, '[mte]')) {
                                        $two_chars = '>=';
                                        $field_value = str_replace('[mte]', '', $field_value);
                                    } else if (stristr($field_value, '[mde]')) {
                                        $two_chars = '>=';
                                        $field_value = str_replace('[mde]', '', $field_value);
                                    } else if (stristr($field_value, '[neq]')) {
                                        $two_chars = '!=';
                                        $field_value = str_replace('[neq]', '', $field_value);
                                    } else if (stristr($field_value, '[eq]')) {
                                        $one_char = '=';
                                        $field_value = str_replace('[eq]', '', $field_value);
                                    } else if (stristr($field_value, '[int]')) {
                                        $field_value = str_replace('[int]', '', $field_value);
                                    } else if (stristr($field_value, '[is]')) {
                                        $one_char = '=';
                                        $field_value = str_replace('[is]', '', $field_value);
                                    } else if (stristr($field_value, '[like]')) {
                                        $two_chars = '%';
                                        $field_value = str_replace('[like]', '', $field_value);
                                    } else if (stristr($field_value, '[null]')) {
                                        $field_value = 'is_null';
                                    } else if (stristr($field_value, '[not_null]')) {
                                        $field_value = 'is_not_null';
                                    } else if (stristr($field_value, '[is_not]')) {
                                        $two_chars = '!%';
                                        $field_value = str_replace('[is_not]', '', $field_value);
                                    }
                                }
                                if ($field_value == 'is_null') {
                                    $where_method = 'where_null';
                                    $field_value = $field_name;
                                } elseif ($field_value == 'is_not_null') {
                                    $where_method = 'where_not_null';
                                    $field_value = $field_name;
                                } else if ($two_chars == '<=' or $two_chars == '=<') {
                                    $where_method = 'where_lte';
                                    $two_char_left = substr($field_value, 0, 2);
                                    if ($two_char_left === '<=' or $two_char_left === '=<') {
                                        $field_value = substr($field_value, 2, $field_value_len);
                                    }
                                } elseif ($two_chars == '>=' or $two_chars == '=>') {
                                    $where_method = 'where_gte';
                                    $two_char_left = substr($field_value, 0, 2);
                                    if ($two_char_left === '>=' or $two_char_left === '=>') {
                                        $field_value = substr($field_value, 2, $field_value_len);
                                    }
                                } elseif ($two_chars == '!=' or $two_chars == '=!') {
                                    $where_method = 'where_not_equal';
                                    $two_char_left = substr($field_value, 0, 2);
                                    if ($two_char_left === '!=' or $two_char_left === '=!') {
                                        $field_value = substr($field_value, 2, $field_value_len);
                                    }
                                } elseif ($two_chars == '!%' or $two_chars == '%!') {
                                    $where_method = 'where_not_like';
                                    $two_char_left = substr($field_value, 0, 2);
                                    if ($two_char_left === '!%' or $two_char_left === '%!') {
                                        $field_value = '%' . substr($field_value, 2, $field_value_len);
                                    } else {
                                        $field_value = '%' . $field_value;
                                    }
                                } elseif ($one_char == '%') {
                                    $where_method = 'where_like';
                                } elseif ($one_char == '>') {
                                    $where_method = 'where_gt';
                                    $one_char_left = substr($field_value, 0, 1);
                                    if ($one_char_left == '>') {
                                        $field_value = substr($field_value, 1, $field_value_len);
                                    }
                                } elseif ($one_char == '<') {
                                    $where_method = 'where_lt';
                                    $one_char_left = substr($field_value, 0, 1);
                                    if ($one_char_left == '<') {
                                        $field_value = substr($field_value, 1, $field_value_len);
                                    }

                                } elseif ($one_char == '=') {
                                    $where_method = 'where_equal';
                                    $one_char_left = substr($field_value, 0, 1);
                                    if ($one_char_left == '=') {
                                        $field_value = substr($field_value, 1, $field_value_len);
                                    }
                                }
                                if ($where_method == false) {
                                    $orm->where_equal($table_alias . '.' . $field_name, $field_value);
                                } else {
                                    $orm->$where_method($table_alias . '.' . $field_name, $field_value);
                                }
                            }
                        } elseif (is_array($field_value)) {
                            $items = array();
                            foreach ($field_value as $field) {
                                $items[] = $field;
                            }
                            if (!empty($items)) {
                                if (count($items) == 1) {
                                    $orm->where_equal($table_alias . '.' . $field_name, reset($items));
                                } else {
                                    $orm->where_in($table_alias . '.' . $field_name, $items);
                                }
                            } else {
                                if (is_string($field_value) or is_int($field_value)) {
                                    $orm->where_equal($table_alias . '.' . $field_name, $field_value);
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($to_search_in_fields != false and $to_search_keyword != false) {
            if (is_string($to_search_in_fields)) {
                $to_search_in_fields = explode(',', $to_search_in_fields);
            }
            // $to_search_keyword = trim($to_search_keyword);
            $to_search_keyword = preg_replace("/(^\s+)|(\s+$)/us", "", $to_search_keyword);

            $to_search_keyword = strip_tags($to_search_keyword);
            $to_search_keyword = str_replace('\\', '', $to_search_keyword);
            $to_search_keyword = str_replace('*', '', $to_search_keyword);

            if ($to_search_keyword != '') {
                $raw_search_query = false;
                if (!empty($to_search_in_fields)) {
                    $raw_search_query = '';
                    $search_vals = array();
                    $search_qs = array();
                    foreach ($to_search_in_fields as $to_search_in_field) {
                        $search_qs[] = " `{$to_search_in_field}` REGEXP ? ";
                        $search_vals[] = $to_search_keyword;
                    }
                    if (!empty($search_qs)) {
                        $raw_search_query = implode($search_qs, ' OR ');
                        $orm->where_raw('(' . $raw_search_query . ')', $search_vals);
                    }
                }
            }
        }


        if ($ids != false) {
            if (is_string($ids)) {
                $ids = explode(',', $ids);
            }
            $orm->where_in($table . '.id', ($ids));
        }
        if ($exclude_ids != false) {
            if (is_string($exclude_ids)) {
                $exclude_ids = explode(',', $exclude_ids);
            }
            $orm->where_not_in($table . '.id', ($exclude_ids));
        }

        if ($group_by == false) {
            if ($count == false and $count_paging == false and $min == false and $max == false and $avg == false) {
                $orm->group_by($table . '.id');
            }
        } else {
            if ($count_paging == false) {
                if (is_string($group_by)) {
                    $group_by = explode(',', $group_by);
                }
                if (is_array($group_by)) {
                    foreach ($group_by as $group) {
                        $orm->group_by($group);
                    }
                }
            }
        }
        if ($count == false and $count_paging == false and $order_by != false) {
            $order_by = urldecode($order_by);
            $order_by = strip_tags($order_by);
            $order_by = str_replace(array('*',';','--'),'',$order_by);
            $orm->order_by_expr($order_by);
        }


        if ($count_paging == true) {
            $ret = $orm->count('*');
            $plimit = $limit;
            if ($plimit != false and $ret != false) {
                $pages_qty = ceil($ret / $plimit);
                return $pages_qty;
            } else {
                return;
            }

        }


        if ($count == false) {
            if ($current_page != false and $current_page > 1) {
                if ($limit != false) {
                    $page_start = ($current_page - 1) * $limit;
                    $page_end = ($page_start) + $limit;
                    $offset = $page_start;
                }
            }
        }
        // convert to int http://idiorm.readthedocs.org/en/latest/querying.html#limits-and-offsets
        if ($no_limit == false) {
            if ($count == false) {
                if ($limit != false) {
                    $orm->limit(intval($limit));
                }
                if ($offset != false) {
                    $orm->offset(intval($offset));
                }
            }
        }
        if ($count != false) {
            return $orm->count();
        } else if ($min != false) {
            return $orm->min($min);
        } else if ($max != false) {
            return $orm->max($max);
        } else if ($avg != false) {
            return $orm->avg($avg);
        } else if ($get_method != false) {
            return $orm->$get_method();
        } else {
            return $orm->find_array();
            // return $orm->find_many();
        }

    }

    function __call($method, $arg = null)
    {

        return $this->$method($arg);

    }

    function with($table)
    {
        return $this->for_table($table);
    }

    function for_table($table)
    {
        $table_real = $this->app->database->real_table_name($table);
        $orm = ORM::for_table($table_real)->table_alias($table);
        return $orm;
    }

    function debug()
    {
        return ORM::get_last_query();
    }

    function getLastQuery()
    {
        return ORM::get_last_query();
    }


}
