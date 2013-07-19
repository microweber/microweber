<?php
namespace mw;

if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
    define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
}

if (!defined("MW_DB_TABLE_NOTIFICATIONS")) {
    define('MW_DB_TABLE_NOTIFICATIONS', MW_TABLE_PREFIX . 'notifications');
}
action_hook('mw_db_init_default', '\mw\Notifications::db_init');
action_hook('mw_db_init', '\mw\Notifications::db_init');


api_expose('/mw/Notifications/delete');
api_expose('/mw/Notifications/save');
api_expose('/mw/Notifications/reset');





class Notifications
{

    static function read($id)
    {
        $params = array();
        $params['id'] = trim($id);
        $params['one'] = true;

        $get = \mw\Notifications::get($params);

        if ($get != false and isset($get['is_read']) and $get['is_read'] == 'n') {
            $save = array();
            $save['id'] = $get['id'];
            $save['is_read'] = 'y';
            $table = MW_DB_TABLE_NOTIFICATIONS;
            mw_var('FORCE_SAVE', $table);
            $data = save_data($table, $save);
            cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');

        }

        return $get;
    }

    static function mark_as_read($module)
    {

        if (($module) != false and $module != '') {

            $table = MW_DB_TABLE_NOTIFICATIONS;

            mw_var('FORCE_SAVE', $table);

            $get_params = array();
            $get_params['table'] = 'table_notifications';
            $get_params['is_read'] = 'n';
            $get_params['fields'] = 'id';
            $get_params['module'] = db_escape_string($module);

            $data = \mw\Notifications::get($get_params);
            if (isarr($data)) {
                foreach ($data as $value) {
                    $save['is_read'] = 'y';
                    $save['id'] = $value['id'];
                    $save['table'] = 'table_notifications';
                    save('table_notifications', $save);

                }
            }

            cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');
            return $data;
        }
    }

    static function reset()
    {

        $is_admin = is_admin();
        if ($is_admin == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MW_DB_TABLE_NOTIFICATIONS;

        $q = "update $table set is_read='n'";
        db_q($q);
        cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');

        return true;

    }

    static function delete($id)
    {

        $is_admin = is_admin();
        if ($is_admin == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MW_DB_TABLE_NOTIFICATIONS;

        db_delete_by_id($table, intval($id), $field_name = 'id');

        cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');

        return true;

    }

    static function delete_for_module($module)
    {

        if (($module) != false and $module != '') {

            $table = MW_DB_TABLE_NOTIFICATIONS;

            mw_var('FORCE_SAVE', $table);

            $get_params = array();
            $get_params['table'] = 'table_notifications';
            $get_params['fields'] = 'id';
            $get_params['module'] = db_escape_string($module);

            $data = \mw\Notifications::get($get_params);
            if (isarr($data)) {
                $ids = array_values_recursive($data);
                $idsi = implode(',', $ids);
                $cleanup = "delete from $table where id IN ({$idsi})";
                db_q($cleanup);
            }

            cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');
            return true;
        }
    }

    static function db_init()
    {

        $function_cache_id = false;
        $args = func_get_args();
        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $function_cache_id = 'notifications_'.__FUNCTION__ . crc32($function_cache_id);
        $cache_content = cache_get_content($function_cache_id, 'db');
        if (($cache_content) != false) {

            return $cache_content;
        }
        $table_name = MW_DB_TABLE_NOTIFICATIONS;
        $fields_to_add = array();
        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('edited_by', 'int(11) default NULL');
        $fields_to_add[] = array('data_type', 'TEXT default NULL');
        $fields_to_add[] = array('title', 'longtext default NULL');
        $fields_to_add[] = array('description', 'TEXT default NULL');
        $fields_to_add[] = array('content', 'TEXT default NULL');
        $fields_to_add[] = array('module', 'TEXT default NULL');

        $fields_to_add[] = array('rel', 'TEXT default NULL');
        $fields_to_add[] = array('rel_id', 'TEXT default NULL');
        $fields_to_add[] = array('notif_count', 'int(11) default 1');

        $fields_to_add[] = array('is_read', "char(1) default 'n'");

        set_db_table($table_name, $fields_to_add);

        db_add_table_index('rel', $table_name, array('rel(55)'));
        db_add_table_index('rel_id', $table_name, array('rel_id(55)'));

        cache_save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }

    static function save($params)
    {

        $params = parse_params($params);

        // if (!isset($params['rel']) and isset($params['module']) and trim($params['module']) != '') {
        // $params['rel'] = 'modules';
        // $params['rel_id'] = $params['module'];
        // }

        //$adm = is_admin();

        $table = MW_DB_TABLE_NOTIFICATIONS;
        mw_var('FORCE_SAVE', $table);

        if (!isset($params['rel']) or !isset($params['rel_id'])) {
            return ('Error: invalid data you must send rel and rel_id as params for \mw\Notifications::save function');
        }
        $old = date("Y-m-d H:i:s", strtotime('-30 days'));
        $cleanup = "delete from $table where created_on < '{$old}'";
        db_q($cleanup);

        if (isset($params['replace'])) {
            if (isset($params['module']) and isset($params['rel']) and isset($params['rel_id'])) {
                unset($params['replace']);
                $rel1 = db_escape_string($params['rel']);
                $module1 = db_escape_string($params['module']);
                $rel_id1 = db_escape_string($params['rel_id']);
                $cleanup = "delete from $table where rel='{$rel1}' and module='{$module1}' and rel_id='{$rel_id1}'";
                db_q($cleanup);


            }

        }


        cache_clean_group('notifications' . DIRECTORY_SEPARATOR . 'global');

        $data = save($table, $params);
        return $data;
    }

    function get_by_id($id)
    {
        $params = array();

        if ($id != false) {
            if (substr(strtolower($id), 0, 4) == 'log_') {

            }

            $params['id'] = db_escape_string($id);
            $params['one'] = true;

            $get = \mw\Notifications::get($params);
            return $get;

        }
    }

    static function get($params)
    {
        $params = parse_params($params);

        // if (!isset($params['rel']) and isset($params['module']) and trim($params['module']) != '') {
        // $params['rel'] = 'modules';
        // $params['rel_id'] = $params['module'];
        // }
        //
        $return = array();
        $is_sys_log = false;
        if (isset($params['id'])) {
            $is_log = substr(strtolower($params['id']), 0, 4);
            if ($is_log == 'log_') {
                $is_sys_log = 1;
                $is_log_id = str_ireplace('log_', '', $params['id']);
                $log_entr = get_log_entry($is_log_id);
                if ($log_entr != false and isset($params['one'])) {
                    return $log_entr;

                } else if ($log_entr != false) {
                    $return[] = $log_entr;
                }
                // d($is_log_id);
            }

        }
        if ($is_sys_log == false) {
            $table = MW_DB_TABLE_NOTIFICATIONS;
            $params['table'] = $table;

            $return = get($params);
        }
        return $return;
    }

}