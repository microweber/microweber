<?php

namespace Microweber\Providers;


use Notifications;

api_expose('notifications_manager/delete');
api_expose('notifications_manager/reset');
api_expose('notifications_manager/read');

api_expose('notifications_manager/mark_all_as_read');


class NotificationsManager
{
    public $app;
    public $table = 'notifications';
    function __construct($app = null)
    {

        if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
            define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
        }




        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }

        }


        if (!defined("MW_DB_TABLE_NOTIFICATIONS_DB_INIT")) {
            define('MW_DB_TABLE_NOTIFICATIONS_DB_INIT', true);
            $this->init_db();
        }


    }

    public function read($id)
    {
        if (defined('MW_API_CALL')) {
            $is_admin = $this->app->user_manager->is_admin();
            if ($is_admin == false) {
                return array('error' => "You must be logged in as admin to perform: " . __CLASS__ . '->' . __FUNCTION__);
            }
        }


        if (is_array($id)) {
            $id = array_pop($id);
        }

        $params = array();
        $params['id'] = trim($id);
        $params['one'] = true;

        $get = $this->get($params);

        if ($get != false and isset($get['is_read']) and $get['is_read'] == 'n') {
            $save = array();
            $save['id'] = $get['id'];
            $save['is_read'] = 'y';
            $table = $this->table;
            mw_var('FORCE_SAVE', $table);
            $data = $this->app->database->save($table, $save);
            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . $data);
            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

        }

        return $get;
    }


    public function mark_as_read($module)
    {

        if (($module) != false and $module != '') {

            $table = $this->table;

            mw_var('FORCE_SAVE', $table);

            $get_params = array();
            $get_params['table'] = $table;
            $get_params['is_read'] = 'n';
            $get_params['fields'] = 'id';
            if ($module != 'all') {

                $get_params['module'] = $this->app->database_manager->escape_string($module);
            }
            $data = $this->get($get_params);


            if (is_array($data)) {
                foreach ($data as $value) {
                    $save['is_read'] = 'y';
                    $save['id'] = $value['id'];
                    $save['table'] = 'table_notifications';
                    $this->app->database->save('table_notifications', $save);
                }
            }

            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
            $this->app->cache_manager->delete('notifications');

            return $data;
        }
    }


    public function mark_all_as_read()
    {

        $is_admin = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $is_admin == false) {
            return array('error' => "You must be logged in as admin to perform: " . __CLASS__ . '->' . __FUNCTION__);
        }

        $table = $this->table;

        $q = "UPDATE $table SET is_read='y' WHERE is_read='n' ";

        $this->app->database->q($q);
        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

        return true;

    }


    public function reset()
    {

        $is_admin = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $is_admin == false) {
            return array('error' => "You must be logged in as admin to perform: " . __CLASS__ . '->' . __FUNCTION__);
        }

        $table = $this->table;

        $q = "UPDATE $table SET is_read='n'";
        $this->app->database->q($q);
        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

        return true;

    }

    public function delete($id)
    {

        $is_admin = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $is_admin == false) {
            return array('error' => "You must be logged in as admin to perform: " . __CLASS__ . '->' . __FUNCTION__);
        }
        if (is_array($id)) {
            $id = array_pop($id);
        }

        $table = $this->table;

        if ($id == 'all') {

            $q = "DELETE FROM $table where id is not NULL  ";

            $this->app->database->q($q);

        } else {
            $this->app->database_manager->delete_by_id($table, intval($id), $field_name = 'id');
        }


        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . intval($id));

        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

        return true;

    }

    public function delete_for_module($module)
    {

        if (($module) != false and $module != '') {

            $table = $this->table;

            mw_var('FORCE_SAVE', $table);

            $get_params = array();
            $get_params['table'] = 'table_notifications';
            $get_params['fields'] = 'id';
            $get_params['module'] = $this->app->database_manager->escape_string($module);

            $data = $this->get($get_params);
            if (is_array($data)) {
                $ids = $this->app->format->array_values($data);
                $idsi = implode(',', $ids);
                $cleanup = "DELETE FROM $table WHERE id IN ({$idsi})";
                $this->app->database->q($cleanup);
            }

            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
            return true;
        }
    }

    public function init_db()
    {
//        static $is_init;
//        if (!$is_init) {
//            $is_init = true;
//            $fields_to_add = array();
//            $fields_to_add['updated_at'] = 'dateTime';
//            $fields_to_add['created_at'] = 'dateTime';
//            $fields_to_add['created_by'] = 'integer';
//            $fields_to_add['edited_by'] = 'integer';
//            $fields_to_add['rel_id'] = 'integer';
//            $fields_to_add['rel_type'] = 'string';
//            $fields_to_add['notif_count'] = 'integer';
//            $fields_to_add['is_read'] = 'integer';
//            $fields_to_add['title'] = 'longText';
//            $fields_to_add['description'] = 'longText';
//            $fields_to_add['content'] = 'longText';
//            $fields_to_add['installed_on'] = 'dateTime';
//
//            app()->database->build_table($this->table, $fields_to_add);
//            return $fields_to_add;
//        }
    }
    public function save($params)
    {

        $params = parse_params($params);

        // if (!isset($params['rel_type']) and isset($params['module']) and trim($params['module']) != '') {
        // $params['rel_type'] = 'modules';
        // $params['rel_id'] = $params['module'];
        // }

        //$adm = $this->app->user_manager->is_admin();

        $table = $this->table;
        mw_var('FORCE_SAVE', $table);

        if (!isset($params['rel_type']) or !isset($params['rel_id'])) {
            return ('Error: invalid data you must send rel and rel_id as params for $this->save function');
        }
        $old = date("Y-m-d H:i:s", strtotime('-30 days'));
        $cleanup = "DELETE FROM $table WHERE created_at < '{$old}'";
        $this->app->database->q($cleanup);

        if (isset($params['replace'])) {
            if (isset($params['module']) and isset($params['rel_type']) and isset($params['rel_id'])) {
                unset($params['replace']);
                $rel1 = $this->app->database_manager->escape_string($params['rel_type']);
                $module1 = $this->app->database_manager->escape_string($params['module']);
                $rel_id1 = $this->app->database_manager->escape_string($params['rel_id']);
                $cleanup = "DELETE FROM $table WHERE rel_type='{$rel1}' AND module='{$module1}' AND rel_id='{$rel_id1}'";
                $this->app->database->q($cleanup);


            }

        }


        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

        $data = $this->app->database->save($table, $params);
        return $data;
    }

    function get_by_id($id)
    {
        $params = array();

        if ($id != false) {
            if (substr(strtolower($id), 0, 4) == 'log_') {

            }

            $params['id'] = $this->app->database_manager->escape_string($id);
            $params['one'] = true;

            $get = $this->get($params);
            return $get;

        }
    }

    public function get_admin($params = false)
    {

    }

    public function get($params = false)
    {
        $params = parse_params($params);

        // if (!isset($params['rel_type']) and isset($params['module']) and trim($params['module']) != '') {
        // $params['rel_type'] = 'modules';
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
                $log_entr = $this->app->log_manager->get_entry_by_id($is_log_id);
                if ($log_entr != false and isset($params['one'])) {
                    return $log_entr;

                } else if ($log_entr != false) {
                    $return[] = $log_entr;
                }
            }

        }
        if ($is_sys_log == false) {
            $table = $this->table;
            $params['table'] = $table;
            $params['order_by'] = 'id desc';
            $return = $this->app->database->get($params);
        }
        return $return;
    }

}


