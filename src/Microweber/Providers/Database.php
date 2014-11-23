<?php




/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\User as DefaultUserProvider;

class Database
{


    public $use_cache = true;


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
     *  $results = $this->get("table=content&is_active=y");
     * </code>
     *
     * @example
     *  <code>
     *  //get users
     *  $results = $this->get("table=users&is_admin=n");
     * </code>
     *
     * @package Database
     */
    public function get($params)
    {
        $orderby = false;
        $cache_group = false;
        $debug = false;
        $getone = false;
        $no_cache = false;
        if (is_string($params)) {
            parse_str($params, $params2);
            $params = $params2;
            extract($params);
        }
        if (!isset($params['table'])) {
            if (!isset($params['from']) and isset($params['to']) and is_string($params['to'])) {
                $params['from'] = $params['to'];
            }
            if (isset($params['from']) and is_string($params['from'])) {
                $fr = $params['from'];
                if (substr(strtolower($fr), 0, 6) != 'table_') {
                    $fr = 'table_' . $fr;
                }
                $params['table'] = $fr;
                unset($params['from']);
            }
        }


        $criteria = array();
        ksort($params);

        foreach ($params as $k => $v) {
            if ($k == 'table') {
                $table = ($v);
            }

            if (!isset($table) and $k == 'what' and !isset($params['rel'])) {
                //  $table = $this->guess_table_name($v);
            }
            if ($k == 'for' and !isset($params['rel'])) {
                $v = trim($v);
                $k = 'rel';
            }
            if ($k == 'rel') {
                $v = trim($v);
            }
            if ($k == 'debug') {
                $debug = ($v);
            }
            if ($k == 'cache_group') {
                if ($no_cache == false) {
                    $cache_group = $v;
                }
            }
            if ($k == 'no_cache') {
                $cache_group = false;
                $no_cache = true;
            }
            if ($k == 'single') {
                $getone = true;
            } else if ($k == 'one') {
                $getone = true;
            } else {
                $criteria[$k] = $v;
            }
            if ('orderby' == $k) {
                $orderby = $v;
            }


            if (isset($this->filter[$k])) {
                if (isset($this->filter[$k]) and is_callable($this->filter[$k])) {
                    $new_criteria = call_user_func($this->filter[$k], $criteria);
                }
                if ($criteria != $new_criteria) {
                    $criteria = $new_criteria;
                }

            }

        }


        if (!isset($table)) {
            print "error no table found in params";
            return false;
        }

        if (isset($params['return_criteria'])) {
            return $criteria;
        }

        if ($cache_group == false and $debug == false) {
            $cache_group = $this->_guess_cache_group($table);
            if (!isset($criteria['id'])) {
                $cache_group = $cache_group . '/global';
            } else {
                $cache_group = $cache_group . '/' . $criteria['id'];
            }

        } else {
            $cache_group = $this->_guess_cache_group($cache_group);
        }

        d(__FILE__);
        //d($criteria);

        $table_criteria = $this->map_array_to_table($table, $criteria);



        $orm = DB::table($table);
        //$orm = DB::table($table)->remember(10);
        $orm = $this->build_query($orm,$table_criteria);


        $get_db_items = $orm->get();
        //dd($get_db_items);
        if (is_integer($get_db_items)) {
            return ($get_db_items);
        }

        if (empty($get_db_items)) {
            return false;
        }

        if ($getone == true) {
            if (is_array($get_db_items)) {
                $one = array_shift($get_db_items);
                return $one;
            }
        }
        return $get_db_items;
    }

    public function build_query($orm, $table_criteria)
    {
        if (!empty($table_criteria)) {
            foreach ($table_criteria as $field_name => $field_value) {
                if ($field_value !== false and $field_name) {

                    if (is_string($field_value) or is_int($field_value)) {

                        $field_value = trim($field_value);
                        $field_value_len = strlen($field_value);

                        $second_char = substr($field_value, 0, 2);
                        $first_char = substr($field_value, 0, 1);
                        $compare_sign = false;
                        $where_method = false;

                        if ($field_value_len > 0) {
                            if (is_string($field_value)) {
                                if (stristr($field_value, '[lt]')) {
                                    $first_char = '<';
                                    $field_value = str_replace('[lt]', '', $field_value);
                                } else if (stristr($field_value, '[lte]')) {
                                    $second_char = '<=';
                                    $field_value = str_replace('[lte]', '', $field_value);
                                } else if (stristr($field_value, '[st]')) {
                                    $first_char = '<';
                                    $field_value = str_replace('[st]', '', $field_value);
                                } else if (stristr($field_value, '[ste]')) {
                                    $second_char = '<=';
                                    $field_value = str_replace('[ste]', '', $field_value);
                                } else if (stristr($field_value, '[gt]')) {
                                    $first_char = '>';
                                    $field_value = str_replace('[gt]', '', $field_value);
                                } else if (stristr($field_value, '[gte]')) {
                                    $second_char = '>=';
                                    $field_value = str_replace('[gte]', '', $field_value);
                                } else if (stristr($field_value, '[mt]')) {
                                    $first_char = '>';
                                    $field_value = str_replace('[mt]', '', $field_value);
                                } else if (stristr($field_value, '[md]')) {
                                    $first_char = '>';
                                    $field_value = str_replace('[md]', '', $field_value);
                                } else if (stristr($field_value, '[mte]')) {
                                    $second_char = '>=';
                                    $field_value = str_replace('[mte]', '', $field_value);
                                } else if (stristr($field_value, '[mde]')) {
                                    $second_char = '>=';
                                    $field_value = str_replace('[mde]', '', $field_value);
                                } else if (stristr($field_value, '[neq]')) {
                                    $second_char = '!=';
                                    $field_value = str_replace('[neq]', '', $field_value);
                                } else if (stristr($field_value, '[eq]')) {
                                    $first_char = '=';
                                    $field_value = str_replace('[eq]', '', $field_value);
                                } else if (stristr($field_value, '[int]')) {
                                    $field_value = str_replace('[int]', '', $field_value);
                                } else if (stristr($field_value, '[is]')) {
                                    $first_char = '=';
                                    $field_value = str_replace('[is]', '', $field_value);
                                } else if (stristr($field_value, '[like]')) {
                                    $second_char = '%';
                                    $field_value = str_replace('[like]', '', $field_value);
                                } else if (stristr($field_value, '[null]')) {
                                    $field_value = 'is_null';
                                } else if (stristr($field_value, '[not_null]')) {
                                    $field_value = 'is_not_null';
                                } else if (stristr($field_value, '[is_not]')) {
                                    $second_char = '!%';
                                    $field_value = str_replace('[is_not]', '', $field_value);
                                }
                            }
                            if ($field_value == 'is_null') {
                                $where_method = 'where_null';
                                $field_value = $field_name;
                            } elseif ($field_value == 'is_not_null') {
                                $where_method = 'where_not_null';
                                $field_value = $field_name;
                            } else if ($second_char == '<=' or $second_char == '=<') {
                                $where_method = 'where_lte';
                                $two_char_left = substr($field_value, 0, 2);
                                if ($two_char_left === '<=' or $two_char_left === '=<') {
                                    $field_value = substr($field_value, 2, $field_value_len);
                                }
                            } elseif ($second_char == '>=' or $second_char == '=>') {
                                $where_method = 'where_gte';
                                $two_char_left = substr($field_value, 0, 2);
                                if ($two_char_left === '>=' or $two_char_left === '=>') {
                                    $field_value = substr($field_value, 2, $field_value_len);
                                }
                            } elseif ($second_char == '!=' or $second_char == '=!') {
                                $where_method = 'where_not_equal';
                                $two_char_left = substr($field_value, 0, 2);
                                if ($two_char_left === '!=' or $two_char_left === '=!') {
                                    $field_value = substr($field_value, 2, $field_value_len);
                                }
                            } elseif ($second_char == '!%' or $second_char == '%!') {
                                $where_method = 'where_not_like';
                                $two_char_left = substr($field_value, 0, 2);
                                if ($two_char_left === '!%' or $two_char_left === '%!') {
                                    $field_value = '%' . substr($field_value, 2, $field_value_len);
                                } else {
                                    $field_value = '%' . $field_value;
                                }
                            } elseif ($first_char == '%') {
                                $where_method = 'where_like';
                            } elseif ($first_char == '>') {
                                $where_method = 'where_gt';
                                $first_char_left = substr($field_value, 0, 1);
                                if ($first_char_left == '>') {
                                    $field_value = substr($field_value, 1, $field_value_len);
                                }
                            } elseif ($first_char == '<') {
                                $where_method = 'where_lt';
                                $first_char_left = substr($field_value, 0, 1);
                                if ($first_char_left == '<') {
                                    $field_value = substr($field_value, 1, $field_value_len);
                                }

                            } elseif ($first_char == '=') {
                                $where_method = 'where_equal';
                                $first_char_left = substr($field_value, 0, 1);
                                if ($first_char_left == '=') {
                                    $field_value = substr($field_value, 1, $field_value_len);
                                }
                            }
                            if ($where_method == false) {
                                $orm->where($field_name, $field_value);
                            } else {
                                $orm->$where_method($field_name, $field_value);
                            }
                        }
                    } elseif (is_array($field_value)) {
                        $items = array();
                        foreach ($field_value as $field) {
                            $items[] = $field;
                        }
                        if (!empty($items)) {
                            if (count($items) == 1) {
                                $orm->where($field_name, reset($items));
                            } else {
                                $orm->where_in($field_name, $items);
                            }
                        } else {
                            if (is_string($field_value) or is_int($field_value)) {
                                $orm->where($field_name, $field_value);
                            }
                        }
                    }
                }
            }
        }
        return $orm;
    }

    public $table_fields = array();

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
     * $table = $this->table_prefix.'content';
     * $data = array();
     * $data['id'] = 1;
     * $data['non_ex'] = 'i do not exist and will be removed';
     * $criteria = $this->map_array_to_table($table, $array);
     * var_dump($criteria);
     * </code>
     */
    public function map_array_to_table($table, $array)
    {


        $arr_key = crc32($table) + crc32(serialize($array));
        if (isset($this->table_fields[$arr_key])) {
            return $this->table_fields[$arr_key];
        }

        if (empty($array)) {

            return false;
        }
        // $table = $this->real_table_name($table);

        if (isset($this->table_fields[$table])) {
            $fields = $this->table_fields[$table];
        } else {
            $fields = $this->get_fields($table);
            $this->table_fields[$table] = $fields;
        }
        if (is_array($fields)) {
            foreach ($fields as $field) {


                $field = strtolower($field);

                if (isset($array[$field])) {
                    if ($array[$field] != false) {

                        $array_to_return[$field] = $array[$field];
                    }

                    if ($array[$field] == 0) {

                        $array_to_return[$field] = $array[$field];
                    }
                }
            }
        }

        if (!isset($array_to_return)) {
            return false;
        } else {
            $this->table_fields[$arr_key] = $array_to_return;
        }
        return $array_to_return;
    }


    /**
     * Gets all field names from a DB table
     *
     * @param $table string
     *            - table name
     * @param array|bool $exclude_fields array
     *            - fields to exclude
     * @return array
     * @author Peter Ivanov
     * @version 1.0
     * @since Version 1.0
     */

    public function get_fields($table, $exclude_fields = false)
    {

        static $ex_fields_static;
        if (isset($ex_fields_static[$table])) {
            return $ex_fields_static[$table];

        }
        $cache_group = 'db/fields';

        if (!$table) {

            return false;
        }

        $key = 'mw_db_get_fields';
        $hash = $table;
        $value = Cache::get($key);

        if (isset($value[$hash])) {
            return $value[$hash];
        }


        $table = $this->real_table_name($table);
        $table = $this->escape_string($table);


        $sql = " show columns from $table ";
        $query = DB::select($sql);


        $fields = $query;


        $exisiting_fields = array();
        if ($fields == false or $fields == NULL) {
            $ex_fields_static[$table] = false;
            return false;
        }

        if (!is_array($fields)) {

            return false;
        }
        foreach ($fields as $fivesdraft) {

            $fivesdraft = (array)$fivesdraft;

            if ($fivesdraft != NULL and is_array($fivesdraft)) {
                $fivesdraft = array_change_key_case($fivesdraft, CASE_LOWER);
                if (isset($fivesdraft['name'])) {
                    $fivesdraft['field'] = $fivesdraft['name'];
                    $exisiting_fields[strtolower($fivesdraft['field'])] = true;
                } else {
                    if (isset($fivesdraft['field'])) {

                        $exisiting_fields[strtolower($fivesdraft['field'])] = true;
                    } elseif (isset($fivesdraft['Field'])) {

                        $exisiting_fields[strtolower($fivesdraft['Field'])] = true;
                    }
                }
            }
        }


        $fields = array();

        foreach ($exisiting_fields as $k => $v) {

            if (!empty($exclude_fields)) {

                if (in_array($k, $exclude_fields) == false) {

                    $fields[] = $k;
                }
            } else {

                $fields[] = $k;
            }
        }
        $ex_fields_static[$table] = $fields;


        $expiresAt = 30;
        $value[$hash] = $fields;
        $cache = Cache::put($key, $value, $expiresAt);

        return $fields;
    }

    private function _guess_cache_group($group)
    {
        return $group;
    }


    /**
     * Escapes a string from sql injection
     *
     * @param string|array $value to escape
     * @return string|array Escaped string
     * @return mixed Es
     * @example
     * <code>
     * //escape sql string
     *  $results = $this->escape_string($_POST['email']);
     * </code>
     *
     *
     *
     * @package Database
     * @subpackage Advanced
     */
    public function escape_string($value)
    {

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->escape_string($v);
            }
            return $value;
        } else {


            if (!is_string($value)) {
                return $value;
            }
            $str_crc = 'esc' . crc32($value);
            if (isset($this->mw_escaped_strings[$str_crc])) {
                return $this->mw_escaped_strings[$str_crc];
            }

            $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
            $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
            $new = str_replace($search, $replace, $value);
            $this->mw_escaped_strings[$str_crc] = $new;
            return $new;
        }
    }

    public $table_prefix;

    public function real_table_name($assoc_name)
    {

        $assoc_name_new = $assoc_name;


        if ($this->table_prefix == false) {
            $this->table_prefix = Config::get('database.connections.mysql.prefix');
        }


        if ($this->table_prefix != false) {
            $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        }

        $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        $assoc_name_new = str_ireplace($this->table_prefix . $this->table_prefix, $this->table_prefix, $assoc_name_new);

        if ($this->table_prefix and $this->table_prefix != '' and stristr($assoc_name_new, $this->table_prefix) == false) {
            $assoc_name_new = $this->table_prefix . $assoc_name_new;
        }

        return $assoc_name_new;
    }

}