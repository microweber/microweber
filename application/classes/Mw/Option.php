<?php
namespace Mw;

action_hook('mw_db_init', mw('Mw\Option')->db_init());


action_hook('mw_db_init_options', 'create_mw_default_options');
api_expose('save_option');
$_mw_global_options_mem = array();
class Option
{


    function __construct()
    {
        if (!defined("MW_DB_TABLE_OPTIONS")) {
            define('MW_DB_TABLE_OPTIONS', MW_TABLE_PREFIX . 'options');
        }
    }


    public function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $cache_content = mw('cache')->get($function_cache_id, 'db');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table_name = MW_DB_TABLE_OPTIONS;

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');

        $fields_to_add[] = array('option_key', 'TEXT default NULL');
        $fields_to_add[] = array('option_value', 'longtext default NULL');
        $fields_to_add[] = array('option_key2', 'TEXT default NULL');
        $fields_to_add[] = array('option_value2', 'longtext default NULL');
        $fields_to_add[] = array('position', 'int(11) default NULL');

        $fields_to_add[] = array('option_group', 'TEXT default NULL');
        $fields_to_add[] = array('name', 'TEXT default NULL');
        $fields_to_add[] = array('help', 'TEXT default NULL');
        $fields_to_add[] = array('field_type', 'TEXT default NULL');
        $fields_to_add[] = array('field_values', 'TEXT default NULL');

        $fields_to_add[] = array('module', 'TEXT default NULL');
        $fields_to_add[] = array('is_system', 'int(1) default 0');

        \mw('Mw\DbUtils')->build_table($table_name, $fields_to_add);

        //\mw('Mw\DbUtils')->add_table_index('option_group', $table_name, array('option_group'), "FULLTEXT");
        //\mw('Mw\DbUtils')->add_table_index('option_key', $table_name, array('option_key'), "FULLTEXT");
        $this->_create_mw_default_options();
        mw('cache')->save(true, $function_cache_id, $cache_group = 'db');
        // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
        return true;

        //print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
    }


    /**
     *
     * Getting options from the database
     *
     * @param $key array|string - if array it will replace the db params
     * @param $option_group string - your option group
     * @param $return_full bool - if true it will return the whole db row as array rather then just the value
     * @param $module string - if set it will store option for module
     * Example usage:
     * $this->get('my_key', 'my_group');
     *
     *
     *
     */

    public function get($key, $option_group = false, $return_full = false, $orderby = false, $module = false)
    {
        if (MW_IS_INSTALLED != true) {
            return false;
        }
        if ($option_group != false) {

            $cache_group = 'options/' . $option_group;

        } else {
            $cache_group = 'options/global';
        }


        global $_mw_global_options_mem;

        if ($_mw_global_options_mem == NULL) {
            $_mw_global_options_mem = array();

        }


        //d($key);
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
        if (isset($_mw_global_options_mem[$function_cache_id])) {
            return $_mw_global_options_mem[$function_cache_id];
        }


        $cache_content = mw('cache')->get($function_cache_id, $cache_group);
        if (($cache_content) == '--false--') {
            return false;
        }
        // $cache_content = false;
        if (($cache_content) != false) {

            return $cache_content;
        }

        // ->'table_options';
        $table = MW_DB_TABLE_OPTIONS;

        if ($orderby == false) {

            $orderby[0] = 'position';

            $orderby[1] = 'ASC';
        }

        $data = array();

        if (is_array($key)) {
            $data = $key;
        } else {
            $data['option_key'] = $key;
        }
        //   $cache_group = 'options/global/' . $function_cache_id;
        $ok1 = '';
        $ok2 = '';
        if ($option_group != false) {
            $option_group = mw('db')->escape_string($option_group);
            $ok1 = " AND option_group='{$option_group}' ";
        }

        if ($module != false) {
            $module = mw('db')->escape_string($module);
            $data['module'] = $module;
            $ok1 = " AND module='{$module}' ";
        }
        $data['limit'] = 1;
        // $get = mw('db')->get_long($table, $data, $cache_group);
        $ok = mw('db')->escape_string($data['option_key']);

        //  $q = "select * from $table where option_key='{$ok}' {$ok1} {$ok2} ";
        $q = "SELECT * FROM $table WHERE option_key IS NOT null  " . $ok1 . $ok2;
        //d($q);
        $q_cache_id = crc32($q);
        $get_all = mw('db')->query($q, $q_cache_id, $cache_group);
        if (!is_array($get_all)) {
            mw('cache')->save('--false--', $function_cache_id, $cache_group);

            return false;
        }
        $get = array();
        foreach ($get_all as $get_opt) {
            if (isset($get_opt['option_key']) and $ok == $get_opt['option_key']) {
                $get[] = $get_opt;
            }
        }


        //

        if (!empty($get)) {

            if ($return_full == false) {
                if (!is_array($get)) {
                    return false;
                }
                $get = $get[0]['option_value'];

                if (isset($get['option_value']) and strval($get['option_value']) != '') {
                    $get['option_value'] = replace_site_vars_back($get['option_value']);
                }
                $_mw_global_options_mem[$function_cache_id] = $get;
                return $get;
            } else {

                $get = $get[0];

                if (isset($get['option_value']) and strval($get['option_value']) != '') {
                    $get['option_value'] = replace_site_vars_back($get['option_value']);
                }

                if (isset($get['field_values']) and $get['field_values'] != false) {
                    $get['field_values'] = unserialize(base64_decode($get['field_values']));
                }
                $_mw_global_options_mem[$function_cache_id] = $get;
                return $get;
            }
        } else {

            //mw('cache')->save('--false--', $function_cache_id, $cache_group);
            $_mw_global_options_mem[$function_cache_id] = false;
            return FALSE;
        }
    }

    function set_default($data)
    {
        $changes = false;

        if (is_array($data)) {
            if (!isset($data['option_group'])) {
                $data['option_group'] = 'other';
            }

            if (isset($data['option_key'])) {
                $check = $this->get($data['option_key'], $option_group = $data['option_group'], $return_full = false, $orderby = false);

                if ($check == false) {

                    $this->save($data);
                }
            }
        } else {
            error('$this->set_default $data param must be array');
        }
        return $changes;
    }

    public function get_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }
        $table = MW_DB_TABLE_OPTIONS;

        $q = "SELECT * FROM $table WHERE id={$id} LIMIT 1 ";
        $function_cache_id = __FUNCTION__ . crc32($q);
        $res1 = false;
        $res = mw('db')->query($q, $cache_id = $function_cache_id, $cache_group = 'options/' . $id);
        if (is_array($res) and !empty($res)) {
            return $res[0];
        }

    }

    public function get_groups($is_system = false)
    {

        $table = MW_DB_TABLE_OPTIONS;
        $is_systemq = '';
        if ($is_system != false) {
            $is_systemq = ' and is_system=1 ';
        } else {
            $is_systemq = ' and is_system=0 ';
        }
        $q = "SELECT option_group FROM $table WHERE module IS NULL";
        $q = $q . $is_systemq . "AND option_group  IS NOT NULL GROUP BY option_group ORDER BY position ASC ";
        $function_cache_id = __FUNCTION__ . crc32($q);
        $res1 = false;

        //d($q);

        $res = mw('db')->query($q, $cache_id = $function_cache_id, $cache_group = 'options/global');
        if (is_array($res) and !empty($res)) {
            $res1 = array();
            foreach ($res as $item) {
                $res1[] = $item['option_group'];
            }
        }
        return $res1;
    }

    public function get_all($params = '')
    {

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
            extract($params);
        }

        $data = $params;
        $table = MW_DB_TABLE_OPTIONS;
        //  $data['debug'] = 1000;
        if (!isset($data['limit'])) {
            $data['limit'] = 1000;
        }
        $get = mw('db')->get_long($table, $data, $cache_group = 'options/global');

        if (!empty($get)) {
            foreach ($get as $key => $value) {
                if (isset($get[$key]['field_values']) and $get[$key]['field_values'] != false) {
                    $get[$key]['field_values'] = unserialize(base64_decode($get[$key]['field_values']));
                }
            }
        }

        return $get;
    }


    /**
     *
     * You can use this function to store options in the database.
     *
     * @param $data array|string
     * Example usage:
     *
     * $option = array();
     * $option['option_value'] = 'my value';
     * $option['option_key'] = 'my_option';
     * $option['option_group'] = 'my_option_group';
     * $this->save($option);
     *
     *
     *
     */

    public function save($data)
    {

        if (defined('MW_API_CALL')) {
            $is_admin = is_admin();
            if ($is_admin == false) {
                return false;
            }
        }

        if (is_string($data)) {
            $data = parse_params($data);
        }


        // p($_POST);
        $option_group = false;
        if (is_array($data)) {

            if (isset($data['for_module_id']) and $data['for_module_id'] != false) {
                //$data['option_group'] = $data['for_module_id'];
                if (isset($data['id'])) {
                    //	unset($data['id']);
                }
            }

            if (strval($data['option_key']) != '') {
                if (strstr($data['option_key'], '|for_module|')) {
                    $ok1 = explode('|for_module|', $data['option_key']);
                    if (isset($ok1[0])) {
                        $data['option_key'] = $ok1[0];
                    }
                    if (isset($ok1[1])) {
                        $data['module'] = $ok1[1];

                        if (isset($data['id']) and intval($data['id']) > 0) {

                            $chck = mw('option')->get('limit=1&module=' . $data['module'] . '&option_key=' . $data['option_key']);
                            if (isset($chck[0]) and isset($chck[0]['id'])) {

                                $data['id'] = $chck[0]['id'];
                            } else {

                                $table = MW_DB_TABLE_OPTIONS;
                                $copy = \mw('Mw\DbUtils')->copy_row_by_id($table, $data['id']);
                                $data['id'] = $copy;
                            }

                        }
                    }

                    //d($ok1);
                }
            }

            if (isset($data['option_type']) and trim($data['option_type']) == 'static') {

                return mw('Mw\Utils\StaticOption')->save($data);

            }

            if (!isset($data['id']) or intval($data['id']) == 0) {
                if (isset($data['option_key']) and isset($data['option_group']) and trim($data['option_group']) != '') {
                    $option_group = $data['option_group'];

                    $this->delete($data['option_key'], $data['option_group']);
                }
            }
            //d($data);
            $table = MW_DB_TABLE_OPTIONS;
            if (isset($data['field_values']) and $data['field_values'] != false) {
                $data['field_values'] = base64_encode(serialize($data['field_values']));
            }

            if (isset($data['module']) and isset($data['option_group']) and isset($data['option_key'])) {
                //$m = mw('db')->escape_string($data['module']);
                $opt_gr = mw('db')->escape_string($data['option_group']);
                $opt_key = mw('db')->escape_string($data['option_key']);
                $clean = "DELETE FROM $table WHERE  option_group='{$opt_gr}' AND  option_key='{$opt_key}'";
                mw('db')->q($clean);
                $cache_group = 'options/' . $opt_gr;
                mw('cache')->delete($cache_group);

                //d($clean);
            }
            //	$data['debug'] = 1;

            //}
            if (strval($data['option_key']) != '') {

                if (isset($data['option_group']) and strval($data['option_group']) == '') {

                    unset($data['option_group']);
                }

                if (isset($data['option_value']) and strval($data['option_value']) != '') {
                    $data['option_value'] = replace_site_vars($data['option_value']);
                    //d($data['option_value']);
                }

                $save = mw('db')->save($table, $data);

                if ($option_group != false) {

                    $cache_group = 'options/' . $option_group;
                    mw('cache')->delete($cache_group);
                } else {
                    $cache_group = 'options/' . 'global';
                    mw('cache')->delete($cache_group);
                }

                if (isset($data['id']) and intval($data['id']) > 0) {

                    $opt = $this->get_by_id($data['id']);

                    if (isset($opt['option_group'])) {
                        $cache_group = 'options/' . $opt['option_group'];
                        mw('cache')->delete($cache_group);
                    }
                    $cache_group = 'options/' . intval($data['id']);
                    mw('cache')->delete($cache_group);
                }


                mw('cache')->delete('options/global');

                return $save;
            }
        }
    }

    public function delete($key, $option_group = false, $module_id = false)
    {
        $key = mw('db')->escape_string($key);

        $table = MW_DB_TABLE_OPTIONS;
        $option_group_q1 = '';
        if ($option_group != false) {
            $option_group = mw('db')->escape_string($option_group);
            $option_group_q1 = "and option_group='{$option_group}'";
        }
        $module_id_q1 = '';
        if ($module_id != false) {
            $module_id = mw('db')->escape_string($module_id);
            $module_id_q1 = "and module='{$module_id}'";
        }

        // $save = $this->saveData ( $table, $data );
        $q = "DELETE FROM $table WHERE option_key='$key' " . $option_group_q1 . $module_id_q1;
        $q = trim($q);

        mw('db')->q($q);

        mw('cache')->delete('options');

        return true;
    }

    private function _create_mw_default_options()
    {

        $function_cache_id = __FUNCTION__;

        $cache_content = mw('cache')->get($function_cache_id, $cache_group = 'db', 'files');
        if (($cache_content) == '--true--') {
            return true;
        }

        $table = MW_DB_TABLE_OPTIONS;

        mw_var('FORCE_SAVE', $table);
        $datas = array();

        $data = array();

        $data['name'] = 'Website name';
        $data['help'] = 'This is very important for the search engines. Your website will be categorized by many criterias and the name is one of it.';
        $data['option_group'] = 'website';
        $data['option_key'] = 'website_title';
        $data['option_value'] = 'Microweber';
        $data['field_type'] = 'text';

        $data['position'] = '1';
        $data['is_system'] = '1';

        $datas[] = $data;

        $data = array();
        $data['option_group'] = 'website';
        $data['option_key'] = 'website_description';
        $data['option_value'] = 'My website\'s description';
        $data['name'] = 'Website description';
        $data['help'] = 'Create Free Online Shop, Free Website and Free Blog with Microweber (MW)';
        $data['field_type'] = 'textarea';
        $data['is_system'] = '1';

        $data['position'] = '2';
        $datas[] = $data;

        $data = array();
        $data['option_group'] = 'website';
        $data['option_key'] = 'website_keywords';
        $data['option_value'] = 'free website, free shop, free blog, make web, mw, microweber';
        $data['name'] = 'Website keywords';
        $data['help'] = 'Write keywords for your site.';
        $data['field_type'] = 'textarea';
        $data['is_system'] = '1';

        $data['position'] = '3';
        $datas[] = $data;

        $data = array();

        $data['name'] = 'Website template';
        $data['help'] = 'This is your current template. You can easy change it anytime.';

        $data['option_group'] = 'template';
        $data['option_key'] = 'curent_template';
        $data['option_value'] = 'default';
        $data['field_type'] = 'website_template';
        $data['position'] = '5';
        $data['is_system'] = '1';

        $datas[] = $data;

        $data = array();
        $data['name'] = 'Items per page';
        $data['help'] = 'Select how many items you want to have per page? example 10,25,50...';

        $data['option_group'] = 'website';
        $data['option_key'] = 'items_per_page';
        $data['option_value'] = '30';
        $data['field_type'] = 'dropdown';
        $data['field_values'] = array('10' => '10', '30' => '30', '50' => '50', '100' => '100', '200' => '200');
        $data['position'] = '6';
        $data['is_system'] = '1';

        $datas[] = $data;

        $data = array();
        $data['option_group'] = 'users';
        $data['option_key'] = 'enable_user_registration';
        $data['name'] = 'Enable user registration';
        $data['help'] = 'You can enable or disable the registration for new users';
        $data['option_value'] = 'y';
        $data['position'] = '10';
        $data['is_system'] = '1';

        $data['field_type'] = 'dropdown';
        $data['field_values'] = array('y' => 'yes', 'n' => 'no');
        $datas[] = $data;


        $changes = false;
        foreach ($datas as $value) {
            $ch = $this->set_default($value);
            if ($ch == true) {
                $changes = true;
            }
        }
        if ($changes == true) {
            //var_dump($changes);
            mw('cache')->delete('options/global');
        }
        mw('cache')->save('--true--', $function_cache_id, $cache_group = 'db', 'files');

        return true;
    }


    public function get_static($key, $option_group = "global")
    {
        $option_group_disabled = mw_var('static_option_disabled_' . $option_group);
        if ($option_group_disabled == true) {
            return false;
        }
        global $mw_static_option_groups;
        $option_group = trim($option_group);
        $option_group = str_replace('..', '', $option_group);

        $fname = $option_group . '.php';

        $dir_name = DBPATH_FULL . 'options' . DS;
        $dir_name_and_file = $dir_name . $fname;
        $key = trim($key);


        if (isset($mw_static_option_groups[$option_group]) and isset($mw_static_option_groups[$option_group][$key])) {
            return ($mw_static_option_groups[$option_group][$key]);
        }


        if (is_file($dir_name_and_file)) {
            $ops_array = file_get_contents($dir_name_and_file);
            if ($ops_array != false) {
                $ops_array = str_replace(CACHE_CONTENT_PREPEND, '', $ops_array);
                if ($ops_array != '') {
                    $ops_array = unserialize($ops_array);
                    if (is_array($ops_array)) {
                        $all_options = $ops_array;
                        $mw_static_option_groups[$option_group] = $all_options;
                        //mw_var('option_disabled_' . $option_group);
                        if (isset($mw_static_option_groups[$option_group]) and isset($mw_static_option_groups[$option_group][$key])) {
                            return ($mw_static_option_groups[$option_group][$key]);
                        } else {
                            $mw_static_option_groups[$option_group][$key] = false;
                        }

                    }
                }
            }
        } else {
            mw_var('static_option_disabled_' . $option_group, true);
        }

    }


    public function save_static($data)
    {

        if (MW_IS_INSTALLED == true) {
            // only_admin_access();
        }
        $data = parse_params($data);

        if (!isset($data['option_key']) or !isset($data['option_value'])) {
            exit("Error: no option_key or option_value");
        }
        if (!isset($data['option_group'])) {
            $data['option_group'] = 'global';
        }
        $data['option_group'] = trim($data['option_group']);
        $data['option_key'] = trim($data['option_key']);
        $data['option_value'] = (htmlentities($data['option_value']));
        //d($data);

        $data['option_group'] = str_replace('..', '', $data['option_group']);

        $fname = $data['option_group'] . '.php';

        //	$dir_name = DBPATH_FULL . 'options' . DS . $data['option_group'] . DS;

        $dir_name = DBPATH_FULL . 'options' . DS;
        $dir_name_and_file = $dir_name . $fname;
        if (is_dir($dir_name) == false) {
            mkdir_recursive($dir_name);
        }
        $data_to_serialize = array();
        if (is_file($dir_name_and_file)) {
            $ops_array = file_get_contents($dir_name_and_file);
            if ($ops_array != false) {
                $ops_array = str_replace(CACHE_CONTENT_PREPEND, '', $ops_array);
                if ($ops_array != '') {
                    $ops_array = unserialize($ops_array);
                    if (is_array($ops_array)) {
                        $data_to_serialize = $ops_array;
                    }
                }
            }
            //d($ops_array);
        }

        $data_to_serialize[$data['option_key']] = $data['option_value'];
        //	d($data_to_serialize);
        $data_to_serialize = serialize($data_to_serialize);

        $option_save_string = CACHE_CONTENT_PREPEND . $data_to_serialize;

        $cache = file_put_contents($dir_name_and_file, $option_save_string);
        return $cache;
    }


}

$mw_static_option_groups = array();