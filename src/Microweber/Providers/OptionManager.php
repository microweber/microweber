<?php

/*
 * This file is part of Microweber
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://microweber.com/license/
 *
 */

namespace Microweber;

namespace Microweber\Providers;

use Option;
use DB;
use Cache;

class OptionManager
{
    public $app;
    public $options_memory = array(); //internal array to hold options in cache 
    public $override_memory = array(); //array to hold options values that are not persistent in DB and changed on runtime
    public $tables = array();
    public $table_prefix = false;
    public $adapters_dir = false;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->set_table_names();
    }

    public function set_table_names($tables = false)
    {
        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['content'])) {
            $tables['options'] = 'options';
        }
        $this->tables = $tables;
        if (!defined('MW_DB_TABLE_OPTIONS')) {
            define('MW_DB_TABLE_OPTIONS', $tables['options']);
        }
    }

    public function get_all($params = '')
    {
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
            extract($params);
        }

        $data = $params;
        $table = $this->tables['options'];
        //  $data['debug'] = 1000;
        if (!isset($data['limit'])) {
            $data['limit'] = 1000;
        }
        $data['cache_group'] = 'options/global';
        $data['table'] = $table;

        $get = $this->app->database_manager->get($data);

        if (!empty($get)) {
            foreach ($get as $key => $value) {
                if (isset($get[$key]['field_values']) and $get[$key]['field_values'] != false) {
                    $get[$key]['field_values'] = unserialize(base64_decode($get[$key]['field_values']));
                }
                if (isset($get[$key]['option_value']) and strval($get[$key]['option_value']) != '') {
                    $get[$key]['option_value'] = $this->app->url_manager->replace_site_url_back($get[$key]['option_value']);
                }
            }
        }

        return $get;
    }

    public function get_groups($is_system = false)
    {
        $table = $this->tables['options'];
        $is_systemq = '';
        if ($is_system != false) {
            $is_systemq = ' and is_system=1 ';
        } else {
            $is_systemq = ' and is_system=0 ';
        }
        $q = "SELECT option_group FROM $table WHERE module IS NULL";
        $q = $q . $is_systemq . 'AND option_group  IS NOT NULL GROUP BY option_group ORDER BY position ASC ';
        $function_cache_id = __FUNCTION__ . crc32($q);
        $res1 = false;

        $res = $this->app->database_manager->query($q, $cache_id = $function_cache_id, $cache_group = 'options/global');
        if (is_array($res) and !empty($res)) {
            $res1 = array();
            foreach ($res as $item) {
                $res1[] = $item['option_group'];
            }
        }

        return $res1;
    }

    public function delete($key, $option_group = false, $module_id = false)
    {
        $key = $this->app->database_manager->escape_string($key);

        $table = $this->tables['options'];
        $table = $this->app->database_manager->real_table_name($table);
        $option_group_q1 = '';
        if ($option_group != false) {
            $option_group = $this->app->database_manager->escape_string($option_group);
            $option_group_q1 = "and option_group='{$option_group}'";
        }
        $module_id_q1 = '';
        if ($module_id != false) {
            $module_id = $this->app->database_manager->escape_string($module_id);
            $module_id_q1 = "and module='{$module_id}'";
        }
        $q = "DELETE FROM $table WHERE option_key='$key' " . $option_group_q1 . $module_id_q1;
        $q = trim($q);

        $this->app->database_manager->q($q);
        $this->app->cache_manager->delete('options');

        return true;
    }



    public function set_default($data)
    {
        $changes = false;

        if (is_array($data)) {
            if (!isset($data['option_group'])) {
                $data['option_group'] = 'other';
            }
            if (isset($data['option_key'])) {
                $check = $this->get($data['option_key'], $option_group = $data['option_group'], $return_full = false, $orderby = false);
                if ($check == false) {
                    $changes = $this->save($data);
                }
            }
        } else {
            return false;
        }

        return $changes;
    }

    /**
     * Getting options from the database.
     *
     * @param $key array|string - if array it will replace the db params
     * @param $option_group string - your option group
     * @param $return_full bool - if true it will return the whole db row as array rather then just the value
     * @param $module string - if set it will store option for module
     * Example usage:
     * $this->get('my_key', 'my_group');
     */
    public function get($key, $option_group = false, $return_full = false, $orderby = false, $module = false)
    {
        if ($option_group != false) {
            $cache_group = 'options/' . $option_group;
        } else {
            $cache_group = 'options/global';
        }
        if ($this->options_memory == null) {
            $this->options_memory = array();
        }

        if (isset($this->override_memory[$option_group]) and isset($this->override_memory[$option_group][$key])) {
            return $this->override_memory[$option_group][$key];
        }


        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'option_' . __FUNCTION__ . '_' . $option_group . '_' . crc32($function_cache_id);
        if (isset($this->options_memory[$function_cache_id])) {
            return $this->options_memory[$function_cache_id];
        }

        $table = $this->tables['options'];

        $data = array();

        if (is_array($key)) {
            $data = $key;
        } else {
            $data['option_key'] = $key;
        }
        $option_key_1 = '';
        $option_key_2 = '';
        if ($option_group != false) {
            $option_group = $this->app->database_manager->escape_string($option_group);
            $data['option_group'] = $option_group;
        }

        if ($module != false) {
            $module = $this->app->database_manager->escape_string($module);
            $data['module'] = $module;
        }

        $data['limit'] = 1;
        $ok = $this->app->database_manager->escape_string($data['option_key']);

        $filter = array();
        //  $filter['limit'] = 1;
        $filter['option_key'] = $key;
        if ($option_group != false) {
            $filter['option_group'] = $option_group;
        }

        if ($module != false) {
            $filter['module'] = $module;
        }
        $filter['table'] = $table;

        $get_all = mw()->database_manager->get($filter);


        if (!is_array($get_all)) {
            return false;
        }
        $get = array();
        foreach ($get_all as $get_opt) {
            if (isset($get_opt['option_key']) and $key == $get_opt['option_key']) {
                $get[] = $get_opt;
            }
        }

        if (!empty($get)) {
            if ($return_full == false) {
                if (!is_array($get)) {
                    return false;
                }

                $get = $get[0]['option_value'];
                if (isset($get['option_value']) and strval($get['option_value']) != '') {
                    $get['option_value'] = $this->app->url_manager->replace_site_url_back($get['option_value']);
                }

                $this->options_memory[$function_cache_id] = $get;

                return $get;
            } else {
                $get = $get[0];

                if (isset($get['option_value']) and strval($get['option_value']) != '') {
                    $get['option_value'] = $this->app->url_manager->replace_site_url_back($get['option_value']);
                }

                if (isset($get['field_values']) and $get['field_values'] != false) {
                    $get['field_values'] = unserialize(base64_decode($get['field_values']));
                }
                $this->options_memory[$function_cache_id] = $get;

                return $get;
            }
        } else {
            $this->options_memory[$function_cache_id] = false;

            return false;
        }
    }

    /**
     * You can use this function to store options in the database.
     *
     * @param $data array|string
     * Example usage:
     *
     * $option = array();
     * $option['option_value'] = 'my value';
     * $option['option_key'] = 'my_option';
     * $option['option_group'] = 'my_option_group';
     * mw()->option_manager->save($option);
     */
    public function save($data)
    {
        if (defined('MW_API_CALL')) {
            $is_admin = $this->app->user_manager->is_admin();
            if ($is_admin == false) {
                return false;
            }
        }

        if (is_string($data)) {
            $data = parse_params($data);
        }

        $option_group = false;
        if (is_array($data)) {
            if (strval($data['option_key']) != '') {
                if (strstr($data['option_key'], '|for_module|')) {
                    $option_key_1 = explode('|for_module|', $data['option_key']);
                    if (isset($option_key_1[0])) {
                        $data['option_key'] = $option_key_1[0];
                    }
                    if (isset($option_key_1[1])) {
                        $data['module'] = $option_key_1[1];
                        if (isset($data['id']) and intval($data['id']) > 0) {
                            $chck = $this->get('limit=1&module=' . $data['module'] . '&option_key=' . $data['option_key']);
                            if (isset($chck[0]) and isset($chck[0]['id'])) {
                                $data['id'] = $chck[0]['id'];
                            } else {
                                $table = $this->tables['options'];
                                $copy = $this->app->database_manager->copy_row_by_id($table, $data['id']);
                                $data['id'] = $copy;
                            }
                        }
                    }
                }
            }

            $delete_content_cache = false;
            if (!isset($data['id']) or intval($data['id']) == 0) {
                if (isset($data['option_key']) and isset($data['option_group']) and trim($data['option_group']) != '') {
                    $option_group = $data['option_group'];

                     $existing = $this->get($data['option_key'], $data['option_group'], $return_full = true);

                    if ($existing == false) {
                        //
                    } elseif (isset($existing['id'])) {
                        $data['id'] = $existing['id'];
                    }
                }
            }

            $table = $this->tables['options'];
            if (isset($data['field_values']) and $data['field_values'] != false) {
                $data['field_values'] = base64_encode(serialize($data['field_values']));
            }
            if (isset($data['module'])) {
                $data['module'] = str_ireplace('/admin', '', $data['module']);
            }

            if (strval($data['option_key']) != '') {
                if ($data['option_key'] == 'current_template') {
                    $delete_content_cache = true;
                }
                if (isset($data['option_group']) and strval($data['option_group']) == '') {
                    unset($data['option_group']);
                }

                if (isset($data['option_value']) and $data['option_value'] != false) {
                    $data['option_value'] = $this->app->url_manager->replace_site_url($data['option_value']);
                }

                $data['allow_html'] = true;
                $data['allow_scripts'] = true;
                $data['table'] = $this->tables['options'];

                $save = $this->app->database_manager->save($data);

                if ($option_group != false) {
                    $cache_group = 'options/' . $option_group;
                    $this->app->cache_manager->delete($cache_group);
                } else {
                    $cache_group = 'options/' . 'global';
                    $this->app->cache_manager->delete($cache_group);
                }
                if ($save != false) {
                    $cache_group = 'options/' . $save;
                    $this->app->cache_manager->delete($cache_group);
                }

                if ($delete_content_cache != false) {
                    $cache_group = 'content/global';
                    $this->app->cache_manager->delete($cache_group);
                }

                if (isset($data['id']) and intval($data['id']) > 0) {
                    $opt = $this->get_by_id($data['id']);
                    if (isset($opt['option_group'])) {
                        $cache_group = 'options/' . $opt['option_group'];
                        $this->app->cache_manager->delete($cache_group);
                    }
                    $cache_group = 'options/' . intval($data['id']);
                    $this->app->cache_manager->delete($cache_group);
                }

                $this->app->cache_manager->delete('options');

                return $save;
            }
        }
    }

    public function get_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        $params = array();
        $params['id'] = $id;
        $params['single'] = true;

        return $this->get_all($params);
    }

    public function get_items_per_page($group = 'website')
    {
        if (!isset($this->options_memory['items_per_page'])) {
            $this->options_memory = array();
        }
        if (isset($this->options_memory['items_per_page'][$group])) {
            return $this->options_memory['items_per_page'][$group];
        }

        if (mw_is_installed() == true) {
            $table = $this->tables['options'];
            $ttl = '99999';

            $cache_key = $table . '_items_per_page_' . $group;
            $items_per_page = Cache::tags($table)->remember($cache_key, $ttl, function () use ($table, $group) {
                $items_per_page = DB::table($table)->where('option_key', 'items_per_page')
                    ->where('option_group', $group)
                    ->first();

                return $items_per_page;
            });

            if (!empty($items_per_page)) {
                $items_per_page = (array)$items_per_page;
                if (isset($items_per_page['option_value'])) {
                    $result = $items_per_page['option_value'];
                    $this->options_memory['items_per_page'][$group] = $result;

                    return $result;
                }
            }
        }
    }


    public function override($option_group, $key, $value)
    {
        if (!isset($this->override_memory[$option_group])) {
            $this->override_memory[$option_group] = array();
        }
        $this->override_memory[$option_group][$key] = $value;
    }
}
