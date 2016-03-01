<?php

namespace Microweber\Providers;

use Notifications;

class NotificationsManager
{
    public $app;
    public $table = 'notifications';

    public function __construct($app = null)
    {
        if (defined('INI_SYSTEM_CHECK_DISABLED') == false) {
            define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
        }

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
    }

    public function read($id)
    {
        if (defined('MW_API_CALL')) {
            $is_admin = $this->app->user_manager->is_admin();
            if ($is_admin == false) {
                return array('error' => 'You must be logged in as admin to perform: '.__CLASS__.'->'.__FUNCTION__);
            }
        }

        if (is_array($id)) {
            $id = array_pop($id);
        }

        $params = array();
        $params['id'] = trim($id);
        $params['one'] = true;

        $get = $this->get($params);

        if ($get != false and isset($get['is_read']) and $get['is_read'] == 0) {
            $save = array();
            $save['id'] = $get['id'];
            $save['is_read'] = 1;
            $table = $this->table;
            $data = $this->app->database_manager->save($table, $save);
            $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.$data);
            $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.'global');
        }

        return $get;
    }

    public function mark_as_read($module)
    {
        if (($module) != false and $module != '') {
            $table = $this->table;
            $get_params = array();
            $get_params['table'] = $table;
            $get_params['is_read'] = 0;
            $get_params['fields'] = 'id';
            if ($module != 'all') {
                $get_params['module'] = $this->app->database_manager->escape_string($module);
            }
            $data = $this->get($get_params);

            if (is_array($data)) {
                foreach ($data as $value) {
                    $save['is_read'] = 1;
                    $save['id'] = $value['id'];
                    $save['table'] = 'notifications';
                    $this->app->database_manager->save('notifications', $save);
                }
            }

            $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.'global');
            $this->app->cache_manager->delete('notifications');

            return $data;
        }
    }

    public function mark_all_as_read()
    {
        $is_admin = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $is_admin == false) {
            return array('error' => 'You must be logged in as admin to perform: '.__CLASS__.'->'.__FUNCTION__);
        }

        \DB::table($this->table)->whereIsRead(0)->update(['is_read' => 1]);
        $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.'global');

        return true;
    }

    public function delete($id)
    {
        $is_admin = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $is_admin == false) {
            return array('error' => 'You must be logged in as admin to perform: '.__CLASS__.'->'.__FUNCTION__);
        }
        if (is_array($id)) {
            $id = array_pop($id);
        }

        $table = $this->table;
        $table = $this->app->database_manager->real_table_name($this->table);

        if ($id == 'all') {
            $q = "DELETE FROM $table where id is not NULL  ";

            $this->app->database_manager->q($q);
        } else {
            $this->app->database_manager->delete_by_id($table, intval($id), $field_name = 'id');
        }

        $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.intval($id));

        $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.'global');

        return true;
    }

    public function delete_for_module($module)
    {
        if (($module) != false and $module != '') {
            $table = $this->table;
            $table = $this->app->database_manager->real_table_name($this->table);

            $get_params = array();
            $get_params['table'] = 'notifications';
            $get_params['fields'] = 'id';
            $get_params['module'] = $this->app->database_manager->escape_string($module);

            $data = $this->get($get_params);
            if (is_array($data)) {
                $ids = $this->app->format->array_values($data);
                $idsi = implode(',', $ids);
                $cleanup = "DELETE FROM $table WHERE id IN ({$idsi})";
                $this->app->database_manager->q($cleanup);
            }

            $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.'global');

            return true;
        }
    }

    public function save($params)
    {
        $params = parse_params($params);

        $table_orig = $this->table;
        $table = $this->app->database_manager->real_table_name($this->table);

        mw_var('FORCE_SAVE', $table);

        if (!isset($params['rel_type']) or !isset($params['rel_id'])) {
            return 'Error: invalid data you must send rel and rel_id as params for $this->save function';
        }

        $old = date('Y-m-d H:i:s', strtotime('-30 days'));
        $cleanup = "DELETE FROM $table WHERE created_at < '{$old}'";
        $this->app->database_manager->q($cleanup);

        if (isset($params['replace'])) {
            if (isset($params['module']) and isset($params['rel_type']) and isset($params['rel_id'])) {
                unset($params['replace']);
                $rel1 = $this->app->database_manager->escape_string($params['rel_type']);
                $module1 = $this->app->database_manager->escape_string($params['module']);
                $rel_id1 = $this->app->database_manager->escape_string($params['rel_id']);
                $cleanup = "DELETE FROM $table WHERE rel_type='{$rel1}' AND module='{$module1}' AND rel_id='{$rel_id1}'";
                $this->app->database_manager->q($cleanup);
            }
        }
        if (!isset($params['is_read'])) {
            $params['is_read'] = 0;
        }

        $this->app->cache_manager->delete('notifications'.DIRECTORY_SEPARATOR.'global');

        $data = $this->app->database_manager->save($table_orig, $params);

        return $data;
    }

    public function get_by_id($id)
    {
        $params = array();

        if ($id != false) {
            $params['id'] = $id;
            $params['one'] = true;

            $get = $this->get($params);

            return $get;
        }
    }

    public function get($params = false)
    {
        $params = parse_params($params);

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
                } elseif ($log_entr != false) {
                    $return[] = $log_entr;
                }
            }
        }

        if (isset($params['rel'])) {
            $params['rel_type'] = $params['rel'];
        }

        if ($is_sys_log == false) {
            $table = $this->table;
            $params['table'] = $table;
            $params['order_by'] = 'id desc';
            $return = $this->app->database_manager->get($params);
        }

        return $return;
    }
}
