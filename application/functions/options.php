<?php

if (!defined("MW_DB_TABLE_OPTIONS")) {
    define('MW_DB_TABLE_OPTIONS', MW_TABLE_PREFIX . 'options');
}

action_hook('mw_db_init_options', 'mw_options_init_db');

function mw_options_init_db()
{
    $function_cache_id = false;

    $args = func_get_args();

    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_content = cache_get_content($function_cache_id, 'db');

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

    set_db_table($table_name, $fields_to_add);

    //db_add_table_index('option_group', $table_name, array('option_group'), "FULLTEXT");
    //db_add_table_index('option_key', $table_name, array('option_key'), "FULLTEXT");

    cache_save(true, $function_cache_id, $cache_group = 'db');
    // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
    return true;

    //print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
}

action_hook('mw_db_init_options', 'create_mw_default_options');
function create_mw_default_options()
{

    $function_cache_id = __FUNCTION__;

    $cache_content = cache_get_content($function_cache_id, $cache_group = 'db', 'files');
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

    /*
     $data = array();
     $data['option_group'] = 'advanced';
     $data['option_key'] = 'cache_location';
     $data['name'] = 'Cache files location';
     $data['help'] = 'You can set custom cache location. Directory must be writable.';
     $data['option_value'] = 'default';
     $data['field_type'] = 'text';
     $data['position'] = '999';
     //$datas[] = $data;

     $data = array();
     $data['option_group'] = 'advanced';
     $data['option_key'] = 'cache_cleanup_interval';
     $data['option_key2'] = 'cronjob';
     $data['position'] = '999';

     $data['name'] = 'Cache cleanup interval in minutes';
     $data['help'] = 'You can set custom cache cleanup interval. Type 0 to disable.';
     $data['option_value'] = 'default';

     $data['field_type'] = 'text';
     //$datas[] = $data;
     */

    $changes = false;
    foreach ($datas as $value) {
        $ch = set_default_option($value);
        if ($ch == true) {
            $changes = true;
        }
    }
    if ($changes == true) {
        //var_dump($changes);
        cache_clean_group('options/global');
    }
    cache_save('--true--', $function_cache_id, $cache_group = 'db', 'files');

    return true;
}

function module_option($key, $module, $option_group = false, $return_full = false, $orderby = false)
{
    return get_option($key, $option_group, $return_full, $orderby, $module);
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
 * get_option('my_key', 'my_group');
 *
 *
 *
 */
$_mw_global_options_mem = array();
function get_option($key, $option_group = false, $return_full = false, $orderby = false, $module = false)
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

    /*
     $cache_content = cache_get_content($function_cache_id, $cache_group);
     if (($cache_content) == '--false--') {
     return false;
     }
     // $cache_content = false;
     if (($cache_content) != false) {

     return $cache_content;
     }*/

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
        $option_group = db_escape_string($option_group);
        $ok1 = " AND option_group='{$option_group}' ";
    }

    if ($module != false) {
        $module = db_escape_string($module);
        $data['module'] = $module;
        $ok1 = " AND module='{$module}' ";
    }
    $data['limit'] = 1;
    // $get = db_get($table, $data, $cache_group);
    $ok = db_escape_string($data['option_key']);
    if ($return_full == true) {
        $q = "select * from $table where option_key='{$ok}' {$ok1} {$ok2} limit 1 ";
    } else {
        $q = "select option_value from $table where option_key='{$ok}' {$ok1} {$ok2} limit 1 ";

    }
  // $get = db_query($q, $function_cache_id, $cache_group);


  //  $q = "select * from $table where option_key='{$ok}' {$ok1} {$ok2} ";
    $q = "select * from $table where option_key is not null {$ok1} {$ok2} ";
    //d($q);
    $q_cache_id = crc32($q);
    $get_all = db_query($q, $q_cache_id, $cache_group);
    if(!isarr($get_all)){
        return false;
    }
    $get  = array();
    foreach($get_all as $get_opt){
        if(isset($get_opt['option_key']) and $ok == $get_opt['option_key']){
            $get[]= $get_opt;
        }
    }


    //

    if (!empty($get)) {

        if ($return_full == false) {
            if (!isarr($get)) {
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
        //cache_save('--false--', $function_cache_id, $cache_group);
        $_mw_global_options_mem[$function_cache_id] = false;
        return FALSE;
    }
}

function set_default_option($data)
{
    $changes = false;

    if (is_array($data)) {
        if (!isset($data['option_group'])) {
            $data['option_group'] = 'other';
        }

        if (isset($data['option_key'])) {
            $check = get_option($data['option_key'], $option_group = $data['option_group'], $return_full = false, $orderby = false);

            if ($check == false) {

                save_option($data);
            }
        }
    } else {
        error('set_default_option $data param must be array');
    }
    return $changes;
}

function get_option_by_id($id)
{
    $id = intval($id);
    if ($id == 0) {
        return false;
    }
    $table = MW_DB_TABLE_OPTIONS;

    $q = "select * from $table where id={$id} limit 1 ";
    $function_cache_id = __FUNCTION__ . crc32($q);
    $res1 = false;
    $res = db_query($q, $cache_id = $function_cache_id, $cache_group = 'options/' . $id);
    if (is_array($res) and !empty($res)) {
        return $res[0];
    }

}

function get_option_groups($is_system = false)
{

    $table = MW_DB_TABLE_OPTIONS;
    $is_systemq = '';
    if ($is_system != false) {
        $is_systemq = ' and is_system=1 ';
    } else {
        $is_systemq = ' and is_system=0 ';
    }
    $q = "select option_group from $table where module IS NULL $is_systemq and option_group  IS NOT NULL group by option_group order by position ASC ";
    $function_cache_id = __FUNCTION__ . crc32($q);
    $res1 = false;

    //d($q);

    $res = db_query($q, $cache_id = $function_cache_id, $cache_group = 'options/global');
    if (is_array($res) and !empty($res)) {
        $res1 = array();
        foreach ($res as $item) {
            $res1[] = $item['option_group'];
        }
    }
    return $res1;
}

function get_options($params = '')
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
    $get = db_get($table, $data, $cache_group = 'options/global');

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
 * save_option($option);
 *
 *
 *
 */
api_expose('save_option');
function save_option($data)
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
    if (isarr($data)) {

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

                        $chck = get_options('limit=1&module=' . $data['module'] . '&option_key=' . $data['option_key']);
                        if (isset($chck[0]) and isset($chck[0]['id'])) {

                            $data['id'] = $chck[0]['id'];
                        } else {

                            $table = MW_DB_TABLE_OPTIONS;
                            $copy = db_copy_by_id($table, $data['id']);
                            $data['id'] = $copy;
                        }

                    }
                }

                //d($ok1);
            }
        }

        if (isset($data['option_type']) and trim($data['option_type']) == 'static') {

            return static_option_save($data);

        }

        if (!isset($data['id']) or intval($data['id']) == 0) {
            if (isset($data['option_key']) and isset($data['option_group']) and trim($data['option_group']) != '') {
                $option_group = $data['option_group'];

                delete_option_by_key($data['option_key'], $data['option_group']);
            }
        }
        //d($data);
        $table = MW_DB_TABLE_OPTIONS;
        if (isset($data['field_values']) and $data['field_values'] != false) {
            $data['field_values'] = base64_encode(serialize($data['field_values']));
        }

        if (isset($data['module']) and isset($data['option_group']) and isset($data['option_key'])) {
            //$m = db_escape_string($data['module']);
            $opt_gr = db_escape_string($data['option_group']);
            $opt_key = db_escape_string($data['option_key']);
            $clean = "delete from $table where      option_group='{$opt_gr}' and  option_key='{$opt_key}'";
            db_q($clean);
            $cache_group = 'options/' . $opt_gr;
            cache_clean_group($cache_group);

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

            $save = save_data($table, $data);

            if ($option_group != false) {

                $cache_group = 'options/' . $option_group;
                cache_clean_group($cache_group);
            } else {
                $cache_group = 'options/' . 'global';
                cache_clean_group($cache_group);
            }

            if (isset($data['id']) and intval($data['id']) > 0) {

                $opt = get_option_by_id($data['id']);

                if (isset($opt['option_group'])) {
                    $cache_group = 'options/' . $opt['option_group'];
                    cache_clean_group($cache_group);
                }
                $cache_group = 'options/' . intval($data['id']);
                cache_clean_group($cache_group);
            }
            //d($cache_group);
            //
            //            if (isset($data['id'])) {
            //                $cache_group = 'options/' . $data['id'];
            //                cache_clean_group($cache_group);
            //            }
            //            if (isset($data['module'])) {
            //                $cache_group = 'options/' . $data['module'];
            //                cache_clean_group($cache_group);
            //            }
            //
            //
            //            if (isset($data['data-option-group'])) {
            //                $cache_group = 'options/' . $data['data-option-group'];
            //                cache_clean_group($cache_group);
            //            }
            //            if (isset($data['option-group'])) {
            //                $cache_group = 'options/' . $data['option-group'];
            //                cache_clean_group($cache_group);
            //            }
            //            if (isset($data['data-module'])) {
            //                $cache_group = 'options/' . $data['data-module'];
            //                cache_clean_group($cache_group);
            //            }
            //            if (isset($data['option_key'])) {
            //                $cache_group = 'options/' . $data['option_key'];
            //                cache_clean_group($cache_group);
            //            }

            cache_clean_group('options/global');

            return $save;
        }
    }
}

function delete_option_by_key($key, $option_group = false, $module_id = false)
{
    $key = db_escape_string($key);

    $table = MW_DB_TABLE_OPTIONS;
    $option_group_q1 = '';
    if ($option_group != false) {
        $option_group = db_escape_string($option_group);
        $option_group_q1 = "and option_group='{$option_group}'";
    }
    $module_id_q1 = '';
    if ($module_id != false) {
        $module_id = db_escape_string($module_id);
        $module_id_q1 = "and module='{$module_id}'";
    }

    // $save = $this->saveData ( $table, $data );
    $q = "delete from $table where option_key='$key' $option_group_q1 $module_id_q1 ";
    $q = trim($q);

    db_q($q);

    cache_clean_group('options');

    return true;
}
