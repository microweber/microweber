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
    protected $app;

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
        $table_prefix = $this->app->config('table_prefix');
        $host = false;
        $username = false;
        $password = false;
        $dbname = false;
        if (isset($con['host'])) {
            $host = $con['host'];
        }
        if (isset($con['user'])) {
            $username = $con['user'];
        }
        if (isset($con['pass'])) {
            $password = $con['pass'];
        }
        if (isset($con['dbname'])) {
            $dbname = $con['dbname'];
        }
        ORM::configure('mysql:host=' . $host . ';dbname=' . $dbname);
        ORM::configure('username', $username);
        ORM::configure('password', $password);
        ORM::configure('caching', true);
        ORM::configure('logging', true);

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

        $table_real = $this->app->db->real_table_name($table);
        $orm = ORM::for_table($table_real)->table_alias($table);
        if (is_string($params)) {
            parse_str($params, $params2);
            $params = $params2;
        }
        $group_by = false;
        $order_by = false;
        $count = false;
        $limit = false;
        $offset = false;
        $min = false;
        $max = false;
        $avg = false;
        if (is_array($params)) {
            if (isset($params['group_by'])) {
                $group_by = $params['group_by'];
                unset($params['group_by']);
            }
            if (isset($params['order_by'])) {
                $order_by = $params['order_by'];
                unset($params['order_by']);
            }
            if (isset($params['count'])) {
                $count = $params['count'];
                unset($params['count']);
            }
            if (isset($params['limit'])) {
                $limit = $params['limit'];
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
        }


        if (is_array($params) and !empty($params)) {
            $joined_tables = array();
            foreach ($params as $k => $v) {
                $joins = explode('.', $k);
                if (isset($joins[1])) {
                    $table_alias = $joins[0];
                    $table_real = $this->app->db->real_table_name($table_alias);
                }
                if (isset($joins[1]) and !in_array($joins[0], $joined_tables) and $joins[0] != $table) {
                    $joined_tables[] = $table_alias;
                    $orm->where_equal($table_alias . '.rel', $table);
                    $orm->join($table_real, array($table_alias . '.rel_id', '=', $table . '.id'), $table_alias);
                }
                if (!isset($joins[1])) {
                    $field_name = $k;
                    $field_value = $v;
                    $table_alias = $table;
                } else if (isset($joins[1])) {
                    $field_name = $joins[1];
                    $field_value = $v;
                }
                if ($field_value and $field_name) {
                    if (is_array($field_value)) {
                        $items = array();
                        foreach ($field_value as $field) {
                            $items[] = $field;
                        }
                        $orm->where_raw($items[0], $items[1]);
                    } elseif (is_string($field_value)) {
                        $field_value = trim($field_value);
                        $field_value_len = strlen($field_value);

                        $two_chars = substr($field_value, 0, 2);
                        $one_char = substr($field_value, 0, 1);
                        $compare_sign = false;
                        if ($field_value_len > 0) {
                            $where_method = false;
                            if ($field_value == 'is_null') {
                                $where_method = 'where_null';
                                $field_value = $field_name;
                            } elseif ($field_value == 'is_not_null') {
                                $where_method = 'where_not_null';
                                $field_value = $field_name;
                            } else if ($two_chars == '<=' or $two_chars == '=<') {
                                $where_method = 'where_lte';
                                $field_value = substr($field_value, 2, $field_value_len);
                            } elseif ($two_chars == '>=' or $two_chars == '=>') {
                                $where_method = 'where_gte';
                                $field_value = substr($field_value, 2, $field_value_len);

                            } elseif ($two_chars == '!=' or $two_chars == '=!') {
                                $where_method = 'where_not_equal';
                                $field_value = substr($field_value, 2, $field_value_len);
                            } elseif ($two_chars == '!%' or $two_chars == '%!') {
                                $where_method = 'where_not_like';
                                $field_value = '%' . substr($field_value, 2, $field_value_len);
                            } elseif ($one_char == '%') {
                                $where_method = 'where_like';
                            } elseif ($one_char == '>') {
                                $where_method = 'where_gt';
                                $field_value = substr($field_value, 1, $field_value_len);
                            } elseif ($one_char == '<') {
                                $where_method = 'where_lt';
                                $field_value = substr($field_value, 1, $field_value_len);
                            }
                            if ($where_method == false) {
                                $orm->where_equal($table_alias . '.' . $field_name, $field_value);
                            } else {
                                $orm->$where_method($table_alias . '.' . $field_name, $field_value);
                            }
                        }
                    }
                }
            }
        }


        if ($group_by == false) {
            if ($min == false and $max == false and $avg == false) {
                $orm->group_by($table . '.id');
            }
        } else {
            $orm->group_by($group_by);
        }
        if ($order_by != false) {
            $orm->order_by_expr($order_by);
        }

        // convert to int http://idiorm.readthedocs.org/en/latest/querying.html#limits-and-offsets
        if ($limit != false) {
            $orm->limit(intval($limit));
        }
        if ($offset != false) {
            $orm->offset(intval($offset));
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

    function with($table)
    {
        return $this->for_table($table);
    }

    function for_table($table)
    {
        $table_real = $this->app->db->real_table_name($table);
        $orm = ORM::for_table($table_real)->table_alias($table);
        return $orm;
    }

    function getLastQuery()
    {

        return ORM::get_last_query();
    }


}
